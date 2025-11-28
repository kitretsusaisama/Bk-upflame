@extends('dashboard.layout')

@section('title', 'Availability Settings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1>{{ __('Availability Settings') }}</h1>
            <p>{{ __('Manage your availability for bookings.') }}</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Weekly Schedule') }}</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ __('Day') }}</th>
                                        <th>{{ __('Working Hours') }}</th>
                                        <th>{{ __('Breaks') }}</th>
                                        <th>{{ __('Available') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ __('Monday') }}</td>
                                        <td>
                                            <div class="form-row">
                                                <div class="col">
                                                    <input type="time" class="form-control" value="09:00">
                                                </div>
                                                <div class="col">
                                                    <input type="time" class="form-control" value="17:00">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-row">
                                                <div class="col">
                                                    <input type="time" class="form-control" value="12:00">
                                                </div>
                                                <div class="col">
                                                    <input type="time" class="form-control" value="13:00">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="mondayAvailable" checked>
                                                <label class="custom-control-label" for="mondayAvailable"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('Tuesday') }}</td>
                                        <td>
                                            <div class="form-row">
                                                <div class="col">
                                                    <input type="time" class="form-control" value="09:00">
                                                </div>
                                                <div class="col">
                                                    <input type="time" class="form-control" value="17:00">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-row">
                                                <div class="col">
                                                    <input type="time" class="form-control" value="12:00">
                                                </div>
                                                <div class="col">
                                                    <input type="time" class="form-control" value="13:00">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="tuesdayAvailable" checked>
                                                <label class="custom-control-label" for="tuesdayAvailable"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('Wednesday') }}</td>
                                        <td>
                                            <div class="form-row">
                                                <div class="col">
                                                    <input type="time" class="form-control" value="09:00">
                                                </div>
                                                <div class="col">
                                                    <input type="time" class="form-control" value="17:00">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-row">
                                                <div class="col">
                                                    <input type="time" class="form-control" value="12:00">
                                                </div>
                                                <div class="col">
                                                    <input type="time" class="form-control" value="13:00">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="wednesdayAvailable" checked>
                                                <label class="custom-control-label" for="wednesdayAvailable"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('Thursday') }}</td>
                                        <td>
                                            <div class="form-row">
                                                <div class="col">
                                                    <input type="time" class="form-control" value="09:00">
                                                </div>
                                                <div class="col">
                                                    <input type="time" class="form-control" value="17:00">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-row">
                                                <div class="col">
                                                    <input type="time" class="form-control" value="12:00">
                                                </div>
                                                <div class="col">
                                                    <input type="time" class="form-control" value="13:00">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="thursdayAvailable" checked>
                                                <label class="custom-control-label" for="thursdayAvailable"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('Friday') }}</td>
                                        <td>
                                            <div class="form-row">
                                                <div class="col">
                                                    <input type="time" class="form-control" value="09:00">
                                                </div>
                                                <div class="col">
                                                    <input type="time" class="form-control" value="17:00">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-row">
                                                <div class="col">
                                                    <input type="time" class="form-control" value="12:00">
                                                </div>
                                                <div class="col">
                                                    <input type="time" class="form-control" value="13:00">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="fridayAvailable" checked>
                                                <label class="custom-control-label" for="fridayAvailable"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('Saturday') }}</td>
                                        <td>
                                            <div class="form-row">
                                                <div class="col">
                                                    <input type="time" class="form-control" value="10:00">
                                                </div>
                                                <div class="col">
                                                    <input type="time" class="form-control" value="14:00">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-row">
                                                <div class="col">
                                                    <input type="time" class="form-control">
                                                </div>
                                                <div class="col">
                                                    <input type="time" class="form-control">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="saturdayAvailable">
                                                <label class="custom-control-label" for="saturdayAvailable"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('Sunday') }}</td>
                                        <td>
                                            <div class="form-row">
                                                <div class="col">
                                                    <input type="time" class="form-control" disabled>
                                                </div>
                                                <div class="col">
                                                    <input type="time" class="form-control" disabled>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-row">
                                                <div class="col">
                                                    <input type="time" class="form-control" disabled>
                                                </div>
                                                <div class="col">
                                                    <input type="time" class="form-control" disabled>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="sundayAvailable" disabled>
                                                <label class="custom-control-label" for="sundayAvailable"></label>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">{{ __('Save Schedule') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Time Slot Settings') }}</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="form-group">
                            <label for="slotDuration">{{ __('Default Slot Duration') }}</label>
                            <select class="form-control" id="slotDuration">
                                <option>15 minutes</option>
                                <option selected>30 minutes</option>
                                <option>45 minutes</option>
                                <option>60 minutes</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="bufferTime">{{ __('Buffer Time Between Appointments') }}</label>
                            <select class="form-control" id="bufferTime">
                                <option>0 minutes</option>
                                <option selected>15 minutes</option>
                                <option>30 minutes</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">{{ __('Save Settings') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection