@extends('layouts.dashboard')

@section('title', 'Provider Reviews')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Customer Reviews</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="review-summary">
                                <div class="review-score">
                                    <h2>4.8</h2>
                                    <div class="stars">
                                        <span>⭐</span><span>⭐</span><span>⭐</span><span>⭐</span><span>⭐</span>
                                    </div>
                                    <p>Based on 124 reviews</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="review-breakdown">
                                <div class="review-bar">
                                    <span>5 stars</span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: 70%"></div>
                                    </div>
                                    <span>87</span>
                                </div>
                                <div class="review-bar">
                                    <span>4 stars</span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: 20%"></div>
                                    </div>
                                    <span>25</span>
                                </div>
                                <div class="review-bar">
                                    <span>3 stars</span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: 7%"></div>
                                    </div>
                                    <span>9</span>
                                </div>
                                <div class="review-bar">
                                    <span>2 stars</span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: 2%"></div>
                                    </div>
                                    <span>2</span>
                                </div>
                                <div class="review-bar">
                                    <span>1 star</span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: 1%"></div>
                                    </div>
                                    <span>1</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Recent Reviews</h4>
                </div>
                <div class="card-body">
                    <div class="review-list">
                        <div class="review-item">
                            <div class="review-header">
                                <div class="reviewer">
                                    <div class="reviewer-avatar">JD</div>
                                    <div class="reviewer-info">
                                        <h5>John Doe</h5>
                                        <div class="stars">
                                            <span>⭐</span><span>⭐</span><span>⭐</span><span>⭐</span><span>⭐</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="review-date">2 days ago</div>
                            </div>
                            <div class="review-content">
                                <p>Dr. Smith was incredibly knowledgeable and took the time to explain everything thoroughly. Highly recommend!</p>
                            </div>
                        </div>
                        <div class="review-item">
                            <div class="review-header">
                                <div class="reviewer">
                                    <div class="reviewer-avatar">JS</div>
                                    <div class="reviewer-info">
                                        <h5>Jane Smith</h5>
                                        <div class="stars">
                                            <span>⭐</span><span>⭐</span><span>⭐</span><span>⭐</span><span>⭐</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="review-date">1 week ago</div>
                            </div>
                            <div class="review-content">
                                <p>Professional and caring. The consultation was very helpful and I feel much more confident about my health now.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection