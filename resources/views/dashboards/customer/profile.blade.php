@extends('dashboard.layout')

@section('title', 'My Profile')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1>{{ __('My Profile') }}</h1>
            <p>{{ __('Manage your profile information and preferences.') }}</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Profile Picture') }}</h5>
                </div>
                <div class="card-body text-center">
                    <img src="https://via.placeholder.com/150" class="rounded-circle mb-3" alt="Profile Picture">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="profilePicture">
                        <label class="custom-file-label" for="profilePicture">{{ __('Choose file') }}</label>
                    </div>
                    <button class="btn btn-primary btn-sm mt-2">{{ __('Upload') }}</button>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5>{{ __('Account Stats') }}</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            {{ __('Total Bookings') }}
                            <span class="badge badge-primary">17</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            {{ __('Favorite Providers') }}
                            <span class="badge badge-warning">3</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            {{ __('Reviews Given') }}
                            <span class="badge badge-success">12</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Personal Information') }}</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="firstName">{{ __('First Name') }}</label>
                                <input type="text" class="form-control" id="firstName" value="John">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="lastName">{{ __('Last Name') }}</label>
                                <input type="text" class="form-control" id="lastName" value="Doe">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">{{ __('Email Address') }}</label>
                            <input type="email" class="form-control" id="email" value="john.doe@example.com">
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">{{ __('Phone Number') }}</label>
                            <input type="tel" class="form-control" id="phone" value="+1 (555) 987-6543">
                        </div>
                        
                        <div class="form-group">
                            <label for="dateOfBirth">{{ __('Date of Birth') }}</label>
                            <input type="date" class="form-control" id="dateOfBirth" value="1985-06-15">
                        </div>
                        
                        <div class="form-group">
                            <label for="address">{{ __('Address') }}</label>
                            <textarea class="form-control" id="address" rows="2">123 Main Street, New York, NY 10001</textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                    </form>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5>{{ __('Notification Preferences') }}</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="form-group">
                            <label>{{ __('Email Notifications') }}</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="emailBookingConfirmations" checked>
                                <label class="custom-control-label" for="emailBookingConfirmations">{{ __('Booking Confirmations') }}</label>
                            </div>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="emailReminders" checked>
                                <label class="custom-control-label" for="emailReminders">{{ __('Appointment Reminders') }}</label>
                            </div>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="emailPromotions">
                                <label class="custom-control-label" for="emailPromotions">{{ __('Promotional Offers') }}</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>{{ __('SMS Notifications') }}</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="smsReminders" checked>
                                <label class="custom-control-label" for="smsReminders">{{ __('Appointment Reminders') }}</label>
                            </div>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="smsPromotions">
                                <label class="custom-control-label" for="smsPromotions">{{ __('Promotional Offers') }}</label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">{{ __('Save Preferences') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection