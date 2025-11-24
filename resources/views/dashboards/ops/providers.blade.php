@extends('layouts.dashboard')

@section('title', 'Provider Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1>{{ __('Provider Management') }}</h1>
                <button class="btn btn-primary" data-toggle="modal" data-target="#createProviderModal">
                    {{ __('Create Provider') }}
                </button>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="{{ __('Search providers...') }}">
                        </div>
                        <div class="col-md-6">
                            <select class="form-control">
                                <option>{{ __('All Statuses') }}</option>
                                <option>{{ __('Active') }}</option>
                                <option>{{ __('Pending') }}</option>
                                <option>{{ __('Suspended') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <x-table :headers="['Name', 'Specialty', 'Status', 'Rating', 'Actions']">
                        <tr>
                            <td>Dr. John Smith</td>
                            <td>Cardiology</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td>4.8</td>
                            <td>
                                <button class="btn btn-sm btn-info">{{ __('View') }}</button>
                                <button class="btn btn-sm btn-warning">{{ __('Edit') }}</button>
                                <button class="btn btn-sm btn-danger">{{ __('Suspend') }}</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Dr. Jane Johnson</td>
                            <td>Dermatology</td>
                            <td><span class="badge badge-warning">Pending</span></td>
                            <td>4.5</td>
                            <td>
                                <button class="btn btn-sm btn-info">{{ __('View') }}</button>
                                <button class="btn btn-sm btn-warning">{{ __('Edit') }}</button>
                                <button class="btn btn-sm btn-success">{{ __('Approve') }}</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Dr. Bob Williams</td>
                            <td>Neurology</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td>4.9</td>
                            <td>
                                <button class="btn btn-sm btn-info">{{ __('View') }}</button>
                                <button class="btn btn-sm btn-warning">{{ __('Edit') }}</button>
                                <button class="btn btn-sm btn-danger">{{ __('Suspend') }}</button>
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

<!-- Create Provider Modal -->
<div class="modal fade" id="createProviderModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Create New Provider') }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="providerName">{{ __('Provider Name') }}</label>
                        <input type="text" class="form-control" id="providerName" required>
                    </div>
                    <div class="form-group">
                        <label for="providerSpecialty">{{ __('Specialty') }}</label>
                        <input type="text" class="form-control" id="providerSpecialty" required>
                    </div>
                    <div class="form-group">
                        <label for="providerEmail">{{ __('Email') }}</label>
                        <input type="email" class="form-control" id="providerEmail" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-primary">{{ __('Create Provider') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection