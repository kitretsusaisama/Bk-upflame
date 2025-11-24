@extends('layouts.dashboard')

@section('title', 'Provider Schedule')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">My Schedule</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="calendar">
                                <div class="calendar-header">
                                    <button class="btn btn-sm btn-outline-secondary">&lt;</button>
                                    <h5>December 2023</h5>
                                    <button class="btn btn-sm btn-outline-secondary">&gt;</button>
                                </div>
                                <div class="calendar-grid">
                                    <div class="calendar-day-header">
                                        <div>Sun</div>
                                        <div>Mon</div>
                                        <div>Tue</div>
                                        <div>Wed</div>
                                        <div>Thu</div>
                                        <div>Fri</div>
                                        <div>Sat</div>
                                    </div>
                                    <div class="calendar-days">
                                        <!-- Calendar days would be generated here -->
                                        <div class="calendar-day">1</div>
                                        <div class="calendar-day">2</div>
                                        <div class="calendar-day">3</div>
                                        <div class="calendar-day">4</div>
                                        <div class="calendar-day">5</div>
                                        <div class="calendar-day">6</div>
                                        <div class="calendar-day">7</div>
                                        <div class="calendar-day">8</div>
                                        <div class="calendar-day">9</div>
                                        <div class="calendar-day">10</div>
                                        <div class="calendar-day">11</div>
                                        <div class="calendar-day">12</div>
                                        <div class="calendar-day">13</div>
                                        <div class="calendar-day">14</div>
                                        <div class="calendar-day booked">15</div>
                                        <div class="calendar-day booked">16</div>
                                        <div class="calendar-day">17</div>
                                        <div class="calendar-day">18</div>
                                        <div class="calendar-day">19</div>
                                        <div class="calendar-day">20</div>
                                        <div class="calendar-day">21</div>
                                        <div class="calendar-day">22</div>
                                        <div class="calendar-day">23</div>
                                        <div class="calendar-day">24</div>
                                        <div class="calendar-day">25</div>
                                        <div class="calendar-day">26</div>
                                        <div class="calendar-day">27</div>
                                        <div class="calendar-day">28</div>
                                        <div class="calendar-day">29</div>
                                        <div class="calendar-day">30</div>
                                        <div class="calendar-day">31</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Availability Settings</h5>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="form-group">
                                            <label for="startTime">Start Time</label>
                                            <input type="time" class="form-control" id="startTime" value="09:00">
                                        </div>
                                        <div class="form-group">
                                            <label for="endTime">End Time</label>
                                            <input type="time" class="form-control" id="endTime" value="17:00">
                                        </div>
                                        <div class="form-group">
                                            <label>Working Days</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="monday" checked>
                                                <label class="form-check-label" for="monday">Monday</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="tuesday" checked>
                                                <label class="form-check-label" for="tuesday">Tuesday</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="wednesday" checked>
                                                <label class="form-check-label" for="wednesday">Wednesday</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="thursday" checked>
                                                <label class="form-check-label" for="thursday">Thursday</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="friday" checked>
                                                <label class="form-check-label" for="friday">Friday</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="saturday">
                                                <label class="form-check-label" for="saturday">Saturday</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="sunday">
                                                <label class="form-check-label" for="sunday">Sunday</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection