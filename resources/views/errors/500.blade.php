@extends('errors::custom-layout')

@section('title', __('Server Error'))
@section('code', 'Server Code: 500')
@section('image', asset('/assets/img/bma-building.png'))
@section('message', __('This site encounter minimal downtime'))
