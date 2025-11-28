@extends('dashboard.layout')

@section('title', 'Workflow Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1>{{ __('Workflow Management') }}</h1>
                <button class="btn btn-primary" data-toggle="modal" data-target="#createWorkflowModal">
                    {{ __('Create Workflow') }}
                </button>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="{{ __('Search workflows...') }}">
                        </div>
                        <div class="col-md-6">
                            <select class="form-control">
                                <option>{{ __('All Statuses') }}</option>
                                <option>{{ __('Active') }}</option>
                                <option>{{ __('Draft') }}</option>
                                <option>{{ __('Archived') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <x-table :headers="['Name', 'Status', 'Steps', 'Instances', 'Actions']">
                        <tr>
                            <td>User Onboarding</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td>5</td>
                            <td>124</td>
                            <td>
                                <button class="btn btn-sm btn-info">{{ __('View') }}</button>
                                <button class="btn btn-sm btn-warning">{{ __('Edit') }}</button>
                                <button class="btn btn-sm btn-secondary">{{ __('Builder') }}</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Provider Verification</td>
                            <td><span class="badge badge-warning">Draft</span></td>
                            <td>3</td>
                            <td>0</td>
                            <td>
                                <button class="btn btn-sm btn-info">{{ __('View') }}</button>
                                <button class="btn btn-sm btn-warning">{{ __('Edit') }}</button>
                                <button class="btn btn-sm btn-secondary">{{ __('Builder') }}</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Booking Process</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td>4</td>
                            <td>89</td>
                            <td>
                                <button class="btn btn-sm btn-info">{{ __('View') }}</button>
                                <button class="btn btn-sm btn-warning">{{ __('Edit') }}</button>
                                <button class="btn btn-sm btn-secondary">{{ __('Builder') }}</button>
                            </td>
                        </tr>
                    </x-table>
                </div>
                
                <div class="card-footer">
                    <nav>
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled"><span class="page-link">{{ __('Previous') }}</span></li>
                            <li class="page-item active"><span class="page-link">1</span></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">{{ __('Next') }}</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Workflow Modal -->
<div class="modal fade" id="createWorkflowModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Create New Workflow') }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="workflowName">{{ __('Workflow Name') }}</label>
                        <input type="text" class="form-control" id="workflowName" required>
                    </div>
                    <div class="form-group">
                        <label for="workflowDescription">{{ __('Description') }}</label>
                        <textarea class="form-control" id="workflowDescription" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-primary">{{ __('Create Workflow') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection