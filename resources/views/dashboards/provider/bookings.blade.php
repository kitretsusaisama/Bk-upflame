@extends('layouts.dashboard')

@section('title', 'My Bookings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1>{{ __('My Bookings') }}</h1>
                <button class="btn btn-primary" data-toggle="modal" data-target="#createBookingModal">
                    {{ __('Create Booking') }}
                </button>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="{{ __('Search bookings...') }}">
                        </div>
                        <div class="col-md-6">
                            <select class="form-control">
                                <option>{{ __('All Statuses') }}</option>
                                <option>{{ __('Confirmed') }}</option>
                                <option>{{ __('Pending') }}</option>
                                <option>{{ __('Cancelled') }}</option>
                                <option>{{ __('Completed') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <x-table :headers="['Patient', 'Date & Time', 'Service', 'Status', 'Actions']">
                        <tr>
                            <td>John Doe</td>
                            <td>2024-01-20 09:00</td>
                            <td>Consultation</td>
                            <td><span class="badge badge-success">Confirmed</span></td>
                            <td>
                                <button class="btn btn-sm btn-info">{{ __('View') }}</button>
                                <button class="btn btn-sm btn-warning">{{ __('Edit') }}</button>
                                <button class="btn btn-sm btn-danger">{{ __('Cancel') }}</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Jane Roe</td>
                            <td>2024-01-20 10:00</td>
                            <td>Follow-up</td>
                            <td><span class="badge badge-warning">Pending</span></td>
                            <td>
                                <button class="btn btn-sm btn-info">{{ __('View') }}</button>
                                <button class="btn btn-sm btn-success">{{ __('Confirm') }}</button>
                                <button class="btn btn-sm btn-danger">{{ __('Cancel') }}</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Bob Smith</td>
                            <td>2024-01-20 11:00</td>
                            <td>Consultation</td>
                            <td><span class="badge badge-success">Confirmed</span></td>
                            <td>
                                <button class="btn btn-sm btn-info">{{ __('View') }}</button>
                                <button class="btn btn-sm btn-warning">{{ __('Edit') }}</button>
                                <button class="btn btn-sm btn-danger">{{ __('Cancel') }}</button>
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

<!-- Create Booking Modal -->
<div class="modal fade" id="createBookingModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Create New Booking') }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="bookingPatient">{{ __('Patient') }}</label>
                        <select class="form-control" id="bookingPatient">
                            <option>John Doe</option>
                            <option>Jane Roe</option>
                            <option>Bob Smith</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="bookingDateTime">{{ __('Date & Time') }}</label>
                        <input type="datetime-local" class="form-control" id="bookingDateTime" required>
                    </div>
                    <div class="form-group">
                        <label for="bookingService">{{ __('Service') }}</label>
                        <select class="form-control" id="bookingService">
                            <option>Consultation</option>
                            <option>Follow-up</option>
                            <option>Procedure</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="bookingNotes">{{ __('Notes') }}</label>
                        <textarea class="form-control" id="bookingNotes" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-primary">{{ __('Create Booking') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection