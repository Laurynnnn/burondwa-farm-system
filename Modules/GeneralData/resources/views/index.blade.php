@extends('layouts.app')

@section('content')
<style>
    .gd-dashboard-row {
        display: flex;
        gap: 2rem;
        justify-content: flex-start;
        margin-top: 3rem;
    }
    .gd-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(50,128,51,0.07);
        padding: 2rem 2rem 1.5rem 2rem;
        min-width: 320px;
        max-width: 350px;
        min-height: 220px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        border: 1px solid #e6e6e6;
    }
    .gd-card-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1.2rem;
        color: #328033;
        letter-spacing: 1px;
    }
    .gd-action-list {
        list-style: none;
        padding: 0;
        margin: 0;
        width: 100%;
    }
    .gd-action-list li {
        display: flex;
        align-items: center;
        margin-bottom: 1.1rem;
        gap: 0.7rem;
    }
    .gd-action-icon {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        color: #fff;
    }
    .gd-action-add { background: #328033; }
    .gd-action-view { background: #1976d2; }
    .gd-action-inactive { background: #e74c3c; }
    .gd-action-link {
        font-weight: 500;
        color: #328033;
        text-decoration: none;
        transition: color 0.2s;
    }
    .gd-action-link:hover { color: #14501a; text-decoration: underline; }
    .gd-action-inactive-label { color: #888; font-weight: 500; }
</style>
<div class="container">
    <h4 class="mt-4 mb-3" style="font-weight:600;letter-spacing:1px;">GENERAL DATA</h4>
    <div class="gd-dashboard-row">
        <!-- Units of Measure Card -->
        <div class="gd-card">
            <div class="gd-card-title">Units of Measure</div>
            <ul class="gd-action-list">
                <li>
                    <span class="gd-action-icon gd-action-add"><i class="fas fa-ruler-combined"></i></span>
                    <a href="{{ route('generaldata.units.create') }}" class="gd-action-link">Add Unit of Measure</a>
                </li>
                <li>
                    <span class="gd-action-icon gd-action-view"><i class="fas fa-list"></i></span>
                    <a href="{{ route('generaldata.units.index') }}" class="gd-action-link">View Units of Measure</a>
                </li>
                <li>
                    <span class="gd-action-icon gd-action-inactive"><i class="fas fa-eye-slash"></i></span>
                    <span class="gd-action-inactive-label">Inactive Units of Measure</span>
                </li>
            </ul>
        </div>
        <!-- Future cards can be added here in the same row -->
    </div>
</div>
@endsection
