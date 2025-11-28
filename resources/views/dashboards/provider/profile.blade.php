@extends('dashboard.layout')

@section('title', 'Provider Profile')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1>{{ __('Provider Profile') }}</h1>
            <p>{{ __('Manage your provider profile information.') }}</p>
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
                    <h5>{{ __('Profile Stats') }}</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            {{ __('Total Bookings') }}
                            <span class="badge badge-primary">142</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            {{ __('Average Rating') }}
                            <span class="badge badge-warning">4.8</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            {{ __('Years of Experience') }}
                            <span class="badge badge-success">10</span>
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
                                <input type="text" class="form-control" id="lastName" value="Smith">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">{{ __('Email Address') }}</label>
                            <input type="email" class="form-control" id="email" value="john.smith@example.com">
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">{{ __('Phone Number') }}</label>
                            <input type="tel" class="form-control" id="phone" value="+1 (555) 123-4567">
                        </div>
                        
                        <div class="form-group">
                            <label for="specialty">{{ __('Specialty') }}</label>
                            <input type="text" class="form-control" id="specialty" value="Cardiology">
                        </div>
                        
                        <div class="form-group">
                            <label for="bio">{{ __('Bio') }}</label>
                            <textarea class="form-control" id="bio" rows="4">Experienced cardiologist with over 10 years of practice. Specialized in preventive cardiology and heart disease management.</textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                    </form>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5>{{ __('Professional Information') }}</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="form-group">
                            <label for="licenseNumber">{{ __('License Number') }}</label>
                            <input type="text" class="form-control" id="licenseNumber" value="CARD-12345">
                        </div>
                        
                        <div class="form-group">
                            <label for="education">{{ __('Education') }}</label>
                            <input type="text" class="form-control" id="education" value="MD, Harvard Medical School">
                        </div>
                        
                        <div class="form-group">
                            <label for="certifications">{{ __('Certifications') }}</label>
                            <textarea class="form-control" id="certifications" rows="3">American Board of Cardiology
American Heart Association</textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection