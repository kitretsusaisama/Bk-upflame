# Monitoring and Observability Setup Guidelines

## Overview

This document provides comprehensive guidelines for setting up monitoring and observability for the enterprise-grade multi-tenant backend platform. The setup includes logging, metrics, tracing, and alerting using industry-standard tools.

## Architecture Overview

```
Application
    ↓
Observability SDKs
    ↓
Telemetry Collectors
    ↓
Monitoring Systems
    ↓
Alerting & Visualization
```

## 1. Logging Infrastructure

### Structured Logging

All application logs should follow a structured format:

```json
{
  "timestamp": "2025-11-20T10:00:00Z",
  "level": "info",
  "service": "booking-service",
  "tenant_id": "tenant123",
  "user_id": "user123",
  "trace_id": "trace123",
  "span_id": "span123",
  "event": "booking_created",
  "data": {
    "booking_id": "booking123",
    "amount": 5000.00
  }
}
```

### Log Levels

- **DEBUG**: Detailed information for diagnosing problems
- **INFO**: General information about application execution
- **WARN**: Warning conditions that may indicate problems
- **ERROR**: Error events that might still allow the application to continue
- **FATAL**: Severe errors that cause the application to terminate

### Log Categories

1. **Security Logs**: Authentication, authorization, data access
2. **Audit Logs**: Business transactions, user actions
3. **Performance Logs**: Response times, throughput
4. **Error Logs**: Application errors, exceptions
5. **Debug Logs**: Detailed diagnostic information

### Implementation Example (Laravel)

```php
<?php

// Custom logger service
class StructuredLogger
{
    public function log($level, $message, $context = [])
    {
        $logEntry = [
            'timestamp' => now()->toISOString(),
            'level' => $level,
            'service' => config('app.name'),
            'tenant_id' => app('tenant')->id ?? null,
            'trace_id' => request()->header('X-Trace-ID'),
            'event' => $message,
            'data' => $context
        ];
        
        \Log::channel('structured')->{$level}(json_encode($logEntry));
    }
    
    public function security($message, $context = [])
    {
        $this->log('info', "security.{$message}", $context);
    }
    
    public function audit($message, $context = [])
    {
        $this->log('info', "audit.{$message}", $context);
    }
}
```

## 2. Metrics Collection

### Key Metrics to Monitor

#### System Metrics
- CPU utilization
- Memory usage
- Disk I/O
- Network I/O
- Garbage collection statistics

#### Application Metrics
- Request rate (RPS)
- Response time (p50, p95, p99)
- Error rate
- Database query performance
- Cache hit/miss ratio
- Queue depth and processing time

#### Business Metrics
- Bookings created
- Successful logins
- Provider onboarding completion rate
- Revenue metrics
- User engagement

### Implementation with Prometheus

#### Laravel Metrics Exporter

```php
<?php

// Metrics service
class MetricsService
{
    protected $registry;
    
    public function __construct()
    {
        $this->registry = new \Prometheus\CollectorRegistry();
    }
    
    public function recordBookingCreated($tenantId, $amount)
    {
        $counter = $this->registry->getOrRegisterCounter(
            'app',
            'bookings_created_total',
            'Total number of bookings created',
            ['tenant_id']
        );
        
        $counter->inc(['tenant_id' => $tenantId]);
        
        $histogram = $this->registry->getOrRegisterHistogram(
            'app',
            'booking_amount',
            'Booking amount distribution',
            ['tenant_id'],
            [500, 1000, 2000, 5000, 10000]
        );
        
        $histogram->observe($amount, ['tenant_id' => $tenantId]);
    }
    
    public function recordResponseTime($endpoint, $duration)
    {
        $histogram = $this->registry->getOrRegisterHistogram(
            'app',
            'http_request_duration_seconds',
            'HTTP request duration',
            ['endpoint'],
            [0.1, 0.5, 1.0, 2.0, 5.0]
        );
        
        $histogram->observe($duration, ['endpoint' => $endpoint]);
    }
}
```

#### Middleware for HTTP Metrics

```php
<?php

class MetricsMiddleware
{
    protected $metricsService;
    
    public function __construct(MetricsService $metricsService)
    {
        $this->metricsService = $metricsService;
    }
    
    public function handle($request, Closure $next)
    {
        $start = microtime(true);
        
        $response = $next($request);
        
        $duration = microtime(true) - $start;
        
        $this->metricsService->recordResponseTime(
            $request->route()->uri,
            $duration
        );
        
        return $response;
    }
}
```

## 3. Distributed Tracing

### Trace Context Propagation

Implement trace context propagation using W3C Trace Context standard:

```
traceparent: 00-0af7651916cd43dd8448eb211c80319c-b7ad6b7169203331-01
tracestate: rojo=00f067aa0ba902b7,congo=t61rcWkgMzE
```

### Implementation with OpenTelemetry

#### Service Initialization

```php
<?php

// Tracing service
class TracingService
{
    protected $tracer;
    
    public function __construct()
    {
        $tracerProvider = new \OpenTelemetry\SDK\Trace\TracerProvider();
        $this->tracer = $tracerProvider->getTracer('booking-service');
    }
    
    public function startBookingSpan($bookingId, $userId)
    {
        $span = $this->tracer->spanBuilder('booking.process')
            ->setAttribute('booking.id', $bookingId)
            ->setAttribute('user.id', $userId)
            ->startSpan();
            
        \OpenTelemetry\Context\Context::setCurrent(
            \OpenTelemetry\Context\Context::getCurrent()->withContextValue($span)
        );
        
        return $span;
    }
    
    public function endSpan($span, $status = null)
    {
        if ($status) {
            $span->setStatus($status);
        }
        
        $span->end();
    }
}
```

#### Controller Integration

```php
<?php

class BookingController extends Controller
{
    protected $tracingService;
    protected $bookingService;
    
    public function __construct(
        TracingService $tracingService,
        BookingService $bookingService
    ) {
        $this->tracingService = $tracingService;
        $this->bookingService = $bookingService;
    }
    
    public function create(Request $request)
    {
        $span = $this->tracingService->startBookingSpan(
            'new-booking',
            $request->user()->id
        );
        
        try {
            $booking = $this->bookingService->create($request->all());
            
            $span->setAttribute('booking.id', $booking->id);
            $span->setStatus(\OpenTelemetry\API\Trace\StatusCode::STATUS_OK);
            
            return response()->json($booking);
        } catch (\Exception $e) {
            $span->setStatus(
                \OpenTelemetry\API\Trace\StatusCode::STATUS_ERROR,
                $e->getMessage()
            );
            
            throw $e;
        } finally {
            $this->tracingService->endSpan($span);
        }
    }
}
```

## 4. Error Tracking with Sentry

### Integration Setup

```php
<?php

// Sentry error tracking service
class SentryService
{
    public function captureException(\Exception $exception, $context = [])
    {
        if (app()->bound('sentry')) {
            $sentry = app('sentry');
            
            // Add context
            foreach ($context as $key => $value) {
                $sentry->configureScope(function (\Sentry\State\Scope $scope) use ($key, $value) {
                    $scope->setExtra($key, $value);
                });
            }
            
            $sentry->captureException($exception);
        }
    }
    
    public function captureMessage($message, $level = 'info', $context = [])
    {
        if (app()->bound('sentry')) {
            $sentry = app('sentry');
            
            // Add context
            foreach ($context as $key => $value) {
                $sentry->configureScope(function (\Sentry\State\Scope $scope) use ($key, $value) {
                    $scope->setExtra($key, $value);
                });
            }
            
            $sentry->captureMessage($message, $level);
        }
    }
}
```

### Exception Handler Integration

```php
<?php

class Handler extends ExceptionHandler
{
    protected $sentryService;
    
    public function report(Exception $exception)
    {
        if (app()->bound('sentry') && $this->shouldReport($exception)) {
            $this->sentryService->captureException($exception, [
                'tenant_id' => app('tenant')->id ?? null,
                'user_id' => auth()->id(),
                'url' => request()->url(),
                'method' => request()->method()
            ]);
        }
        
        parent::report($exception);
    }
}
```

## 5. Alerting System

### Alert Categories

#### Critical Alerts (Page immediately)
- Service unavailable
- Database connection failures
- High error rates (>5%)
- Security incidents

#### Warning Alerts (Page during business hours)
- Performance degradation
- Resource utilization thresholds
- Queue backlogs

#### Info Alerts (Log only)
- New tenant provisioning
- Feature usage statistics
- Scheduled maintenance

### Alerting Rules Examples

#### Prometheus Alert Rules

```yaml
groups:
- name: backend-alerts
  rules:
  - alert: HighErrorRate
    expr: rate(http_requests_total{status=~"5.."}[5m]) / rate(http_requests_total[5m]) > 0.05
    for: 5m
    labels:
      severity: critical
    annotations:
      summary: "High error rate on {{ $labels.service }}"
      description: "{{ $value }}% of requests are failing"
      
  - alert: HighLatency
    expr: histogram_quantile(0.95, rate(http_request_duration_seconds_bucket[5m])) > 2
    for: 5m
    labels:
      severity: warning
    annotations:
      summary: "High latency on {{ $labels.service }}"
      description: "95th percentile latency is {{ $value }} seconds"
      
  - alert: DatabaseDown
    expr: up{job="database"} == 0
    for: 1m
    labels:
      severity: critical
    annotations:
      summary: "Database is down"
      description: "Database service is not responding"
```

## 6. Visualization with Grafana

### Dashboard Structure

#### System Overview Dashboard
- CPU, memory, disk usage
- Network I/O
- Application uptime

#### API Performance Dashboard
- Request rate by endpoint
- Response time percentiles
- Error rates

#### Business Metrics Dashboard
- Bookings by tenant
- Revenue metrics
- User engagement

#### Tenant Isolation Dashboard
- Resource usage by tenant
- Performance by tenant
- Error rates by tenant

### Sample Grafana Dashboard JSON

```json
{
  "dashboard": {
    "title": "Backend Service Overview",
    "panels": [
      {
        "title": "Request Rate",
        "type": "graph",
        "targets": [
          {
            "expr": "rate(http_requests_total[5m])",
            "legendFormat": "{{ endpoint }}"
          }
        ]
      },
      {
        "title": "Error Rate",
        "type": "graph",
        "targets": [
          {
            "expr": "rate(http_requests_total{status=~\"5..\"}[5m])",
            "legendFormat": "{{ endpoint }}"
          }
        ]
      },
      {
        "title": "Response Time (95th Percentile)",
        "type": "graph",
        "targets": [
          {
            "expr": "histogram_quantile(0.95, rate(http_request_duration_seconds_bucket[5m]))",
            "legendFormat": "{{ endpoint }}"
          }
        ]
      }
    ]
  }
}
```

## 7. Health Checks

### Application Health Endpoint

```php
<?php

class HealthCheckController extends Controller
{
    public function index()
    {
        $checks = [
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'queue' => $this->checkQueue(),
            'storage' => $this->checkStorage()
        ];
        
        $healthy = !in_array(false, array_column($checks, 'healthy'));
        
        return response()->json([
            'status' => $healthy ? 'healthy' : 'unhealthy',
            'checks' => $checks,
            'timestamp' => now()->toISOString()
        ], $healthy ? 200 : 503);
    }
    
    protected function checkDatabase()
    {
        try {
            DB::connection()->getPdo();
            return ['healthy' => true, 'message' => 'Database connection successful'];
        } catch (\Exception $e) {
            return ['healthy' => false, 'message' => $e->getMessage()];
        }
    }
    
    protected function checkCache()
    {
        try {
            Cache::put('health_check', 'ok', 10);
            $result = Cache::get('health_check');
            return ['healthy' => $result === 'ok', 'message' => 'Cache operational'];
        } catch (\Exception $e) {
            return ['healthy' => false, 'message' => $e->getMessage()];
        }
    }
    
    // Additional checks...
}
```

## 8. Log Aggregation with ELK Stack

### Logstash Configuration

```ruby
input {
  file {
    path => "/var/log/application/*.log"
    start_position => "beginning"
    codec => "json"
  }
}

filter {
  json {
    source => "message"
  }
  
  date {
    match => [ "timestamp", "ISO8601" ]
  }
  
  mutate {
    rename => { "data" => "log_data" }
  }
}

output {
  elasticsearch {
    hosts => ["localhost:9200"]
    index => "application-logs-%{+YYYY.MM.dd}"
  }
}
```

### Kibana Dashboard Examples

1. **Security Dashboard**: Failed login attempts, suspicious activities
2. **Performance Dashboard**: Slow queries, API response times
3. **Business Dashboard**: Booking trends, revenue metrics
4. **Error Dashboard**: Exception patterns, error correlations

## 9. Container Monitoring (Docker/Kubernetes)

### Docker Stats Collection

```yaml
version: '3.8'
services:
  app:
    image: backend-app:latest
    logging:
      driver: "json-file"
      options:
        max-size: "10m"
        max-file: "3"
    deploy:
      resources:
        limits:
          cpus: '0.5'
          memory: 512M
        reservations:
          cpus: '0.25'
          memory: 256M
```

### Kubernetes Monitoring

#### Prometheus Operator ServiceMonitor

```yaml
apiVersion: monitoring.coreos.com/v1
kind: ServiceMonitor
metadata:
  name: backend-monitor
  labels:
    app: backend
spec:
  selector:
    matchLabels:
      app: backend
  endpoints:
  - port: metrics
    interval: 30s
```

## 10. Implementation Roadmap

### Phase 1: Basic Monitoring (Week 1-2)
- [x] Structured logging implementation
- [x] Basic metrics collection
- [x] Health check endpoints
- [x] Sentry integration

### Phase 2: Advanced Monitoring (Week 3-4)
- [x] Distributed tracing setup
- [x] Grafana dashboard creation
- [x] Alerting rule configuration
- [x] Log aggregation

### Phase 3: Optimization (Week 5-6)
- [x] Performance tuning based on metrics
- [x] Alert threshold optimization
- [x] Dashboard refinement
- [x] Documentation completion

This monitoring and observability setup provides comprehensive visibility into the platform's operation, enabling proactive issue detection and performance optimization.