@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb :pageTitle="[['name' => 'Categories', 'link'=> '#']]" />
    <x-calender-area />
@endsection
