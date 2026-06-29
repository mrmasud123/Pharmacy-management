@extends('layouts.app')
{{--{{ dd('file loaded', $name ?? 'name not found', $admin ?? 'admin not found') }}--}}

@section('content')
<x-common.page-breadcrumb :pageTitle="[['name' => 'Profile', 'link'=> '#']]" />
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/3 lg:p-6">
{{--        <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-7">Profile </h3>--}}

        <x-profile.profile-card :admin="$admin" />
        <x-profile.personal-info-card :admin="$admin" />
{{--        <x-profile.address-card :admin="$admin" />--}}
    </div>
@endsection
