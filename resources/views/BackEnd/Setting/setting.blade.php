@extends('BackEnd.Layouts.layout')

@section('title', 'Site Setting Management')

@section('content')
    <div class="p-5">
        <div class="container-p-y">
            <div class="mb-4">
                <h4 class="mb-0">
                    <i class="fa-solid fa-list-ul"></i> Site Settings
                </h4>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('settings.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label>Logo</label>
                            <input type="file" name="logo" class="form-control">
                            @error('logo')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            @if ($setting && $setting->logo)
                                <img src="{{ asset('uploads/settings/' . $setting->logo) }}" width="100" class="mt-2">
                            @endif
                        </div>

                        <div class="mb-3">
                            <label>Favicon</label>
                            <input type="file" name="favaicon" class="form-control">
                            @error('favaicon')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            @if ($setting && $setting->favaicon)
                                <img src="{{ asset('uploads/settings/' . $setting->favaicon) }}" width="50"
                                    class="mt-2">
                            @endif
                        </div>
                        <div class="mb-3">
                            <label>Currency</label>
                            <select name="currency" class="form-select">
                                <option value="BDT" {{ $setting?->currency == 'BDT' ? 'selected' : '' }}>
                                    BDT
                                </option>
                                <option value="USD" {{ $setting?->currency == 'USD' ? 'selected' : '' }}>
                                    USD
                                </option>
                            </select>
                            @error('currency')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label>Footer Text</label>
                            <textarea name="footer_text" class="form-control" rows="4">{{ $setting?->footer_text }}</textarea>
                            @error('footer_text')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        @can('general-settings-create')
                            <button type="submit" class="btn btn-primary">
                                Save Settings
                            </button>
                        @endcan
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
