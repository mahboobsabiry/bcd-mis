@extends('layouts.website.layout')
@section('title', $announcement->title)
@section('content')
    <!--==========================
     Content Section
    ============================-->
    <section id="services">
        <div class="container">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('website.index') }}"><i class="fa fa-home"></i><span class="sr-only">صفحه اصلی</span></a>
                </li>
                <li class="breadcrumb-item">
                    <a>اعلامیه</a>
                </li>
            </ol>
            <!--/==/ End of Breadcrumb -->

            <div class="section-header text-right">
                <h2>اعلامیه</h2>
            </div>

            <div class="row">
                <div class="col-lg-10">
                    <!-- Content -->
                    <div class="text-right">
                        <div class="mb-3">
                            <span class="text-muted"><i class="fa fa-clock"></i> {{ \Morilog\Jalali\Jalalian::fromCarbon($announcement->created_at)->format('l Y/m/d h:i') }}</span>
                        </div>
                        <div class="flex-0">
                            <img class="img-fluid" src="{{ asset('website/images/announcements/' . $announcement->img) }}" alt="">
                        </div>

                        <h4 class="mt-2">{{ $announcement->title }}</h4>
                        <p>{!! $announcement->body !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/==/ End of Content -->
@endsection
