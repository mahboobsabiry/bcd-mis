@extends('layouts.website.layout')

@section('content')
    <!-- Intro Slider -->
    <section id="intro">
        <div class="intro-content">
            <h2>ریاست گمرک ولایت بلخ</h2>
{{--            <div>--}}
{{--                <a href="#about" class="btn-get-started scrollto">Get Started</a>--}}
{{--                <a href="#portfolio" class="btn-projects scrollto">Our Projects</a>--}}
{{--            </div>--}}
            <p class="font-weight-bold text-black">جمع‌آوری عواید، مدیریت و کنترول اجناس وارداتی، صادراتی و ترانزیتی در خاک افغانستان</p>
        </div>

        <div id="intro-carousel" class="owl-carousel" >
            <div class="item" style="background-image: url('{{ asset('website/img/intro-carousel/intro-1.jpg') }}');"></div>
            <div class="item" style="background-image: url('{{ asset('website/img/intro-carousel/intro-2.jpg') }}');"></div>
        </div>
    </section>
    <!--/==/ End of Intro Slider -->

    <!-- Main Content -->
    <main id="main">
        <!--==========================
          About Section
        ============================-->
        <section id="about" class="wow fadeInUp">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 about-img">
                        <img src="{{ asset('website/img/about-image.png') }}" alt="">
                    </div>

                    <div class="col-lg-6 content text-right">
                        <h2 class="text-right">درباره گمرک بلخ</h2>
                        <h3>گمرک بلخ نقش کلیدی در تنظیم و مدیریت تجارت ایفا می‌کنند. این گمرک به بهبود اقتصاد کشور از طریق تسهیل ورود و خروج کالاها کمک می‌کنند، در حالی که با جمع‌آوری عوارض و مالیات‌ها و حفظ امنیت مرزی نیز نقش حفاظتی دارد.</h3>

                        <ul class="float-right">
                            <li><i class="ion-android-checkmark-circle float-right"></i>&nbsp; جمع‌آوری عوارض و مالیات&nbsp;</li>
                            <li><i class="ion-android-checkmark-circle float-right"></i>&nbsp;حفظ امنیت و بهداشت عمومی&nbsp;</li>
                            <li><i class="ion-android-checkmark-circle float-right"></i>&nbsp;تسهیل تجارت&nbsp;</li>
                            <li><i class="ion-android-checkmark-circle float-right"></i>&nbsp;پیشگیری از قاچاق&nbsp;</li>
                            <li><i class="ion-android-checkmark-circle float-right"></i>&nbsp;حمایت از تولیدات داخلی&nbsp;</li>
                        </ul>

                    </div>
                </div>

            </div>
        </section><!-- #about -->

        <!--==========================
          Contact Section
        ============================-->
        <section id="contact" class="wow fadeInUp">
            <div class="container">
                <div class="section-header">
                    <h2>تماس با ما</h2>
                    <p>برای معلومات بیشتر در ارتباط باشید.</p>
                </div>

                <div class="row contact-info">

                    <div class="col-md-4">
                        <div class="contact-address">
                            <i class="ion-ios-location-outline"></i>
                            <h3>آدرس</h3>
                            <address>شهرک بندری حیرتان جوار تاسیسات غضنفر، بلخ، افغانستان</address>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="contact-phone">
                            <i class="ion-ios-telephone-outline"></i>
                            <h3>شماره تماس</h3>
                            <p><a href="tel:+155895548855">+70 *** ****</a></p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="contact-email">
                            <i class="ion-ios-email-outline"></i>
                            <h3>ایمیل</h3>
                            <p><a href="mailto:info@bcd.gov.af">info@bcd.gov.af</a></p>
                        </div>
                    </div>

                </div>
            </div>

            <div class="container mb-4">
                <iframe src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d1526.849951778656!2d67.40417053872538!3d37.20417099303362!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMzfCsDEyJzE1LjAiTiA2N8KwMjQnMTkuNyJF!5e1!3m2!1sen!2sus!4v1735247662392!5m2!1sen!2sus" width="100%" height="380" frameborder="0" style="border:0" allowfullscreen></iframe>
            </div>

            <div class="container">
                <div class="form">
                    <div id="sendmessage">پیام شما ارسال شد. تشکر!</div>
                    <div id="errormessage"></div>
                    <form action="" method="post" role="form" class="contactForm">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" data-rule="minlen:4" data-msg="Please enter at least 4 chars" />
                                <div class="validation"></div>
                            </div>
                            <div class="form-group col-md-6">
                                <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" data-rule="email" data-msg="Please enter a valid email" />
                                <div class="validation"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" data-rule="minlen:4" data-msg="Please enter at least 8 chars of subject" />
                            <div class="validation"></div>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" name="message" rows="5" data-rule="required" data-msg="Please write something for us" placeholder="Message"></textarea>
                            <div class="validation"></div>
                        </div>
                        <div class="text-center"><button type="submit">ارسال پیام</button></div>
                    </form>
                </div>

            </div>
        </section><!-- #contact -->
    </main>
@endsection
