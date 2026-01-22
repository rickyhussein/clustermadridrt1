@extends('layouts.app')
@section('back')
    <a href="{{ url('/dashboard-user') }}" class="back-btn"> <i class="icon-left"></i> </a>
@endsection
@section('container')
    <br>
    <br>
    <div class="mt-1 box-settings-profile style1">
        <a class="list-setting-profile" href="{{ url('/my-profile') }}">
            <div class="inner-left">
                <h4 class="fw_6">Profile</h4>
            </div>
            <span class="inner-right"><i class="icon-right"></i></span>

        </a>
        <a class="list-setting-profile" href="{{ url('/ganti-password') }}">
            <div class="inner-left">
                <h4 class="fw_6">Ganti Password</h4>
            </div>
            <span class="inner-right"><i class="icon-right"></i></span>

        </a>
        <a class="list-setting-profile" href="#" id="btn-logout">
            <h4 class="fw_6 critical_color">Log Out</h4>
            <span class="inner-right"> <i class="icon-right"></i> </span>
        </a>
    </div>


    <div class="tf-panel logout">
        <div class="panel_overlay"></div>
          <div class="panel-box panel-center panel-logout">
                <div class="heading">
                    <h2 class="text-center">Do you really want to sign out of your account?</h2>
                </div>
                <div class="bottom">
                    <a class="clear-panel" href="#">Cancel</a>
                    <a class="clear-panel critical_color" href="{{ url('/logout') }}">Log Out</a>
                </div>

          </div>
    </div>

@endsection
