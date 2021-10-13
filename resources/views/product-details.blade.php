@if (isMobile())

    @include('mobile.product-details')

{{ die }}
@endif






@extends('layouts.common')

@section('title', $product->product_name)

@section('css-js')
    <style>
        .cursorshade{
            border: none !important;
        }
        .magnifier{
            position: fixed !important;
            margin: auto !important;
            top: 0 !important;
            bottom: 0 !important;
        }
    </style>
@endsection

@php
    $counter = 1;
    $counter2 = 1;
    
    $est_dt = new DateTime();
    $est_dt->modify( '+10 days' );
@endphp

@section('modals')
    <!-- Modal -->
    <div class="modal fade" id="AddAnswerModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('answer-submit') }}" method="post" id="SubmitAnswerForm">
                    <input type="hidden" name="question_id">
                    <input type="hidden" name="fetchData" value="{{ route('get-add-answer-form-details') }}">@csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Contribute Your Answer</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="" style="padding: 1rem;">
                    
                        <div class="form-group" >
                          <label for="Question"></label>
                          <input type="text" readonly
                            class="form-control" name="question" id="question" aria-describedby="helpId" placeholder="">
                        </div>

                        <div class="form-group">
                          <label for="Question"></label>
                          <input type="text"
                            class="form-control" name="answer" id="answer" aria-describedby="helpId" placeholder="">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>   
                </form>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="PostQuestionModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('qna.new-question-submit') }}" id="QuestionSubmitForm"> @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Post Your Question</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                    
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div class="form-group w-100">
                            <label for="">Question</label>
                            <input type="text"
                                class="form-control" name="question" id="ask_question" aria-describedby="helpId" placeholder="">
                                <small class="text-muted">Type Your Question</small>
                            </div>
                       
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection



@section('content')

@livewire('basic-helper')

<div class="product-details pt-20 pb-90">
    <div class="container" >
        <div class="row">
            {{-- Images section Start --}}
            <div class="col-md-12 col-lg-6 col-12">

                <div class="product_img_slider">
                    <!-- All Images list -->
                    <div class="row">
                        <div class="col-2">
                            <ul>
                                @foreach ($images as $image)
                                <li>
                                    <div class="images-menu cursor-pointer prod-back-div small_img" 
                                        style="background-image: url('{{ productImage($image) }}');" >
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-10">
                            <div class="" style="height: 475px; text-align: center;">
                               <div style="width: 100%; height: 475px; text-align: center; line-height: 475px;">
                                   <img src="{{ productImage($images[0] ?? null) }}"
                                   class="big_img" id="big_img" style="vertical-align: middle;">
                               </div>
                                
                             
                            </div>
                            
                            <div class="buy-now-btn-container">
                                @if ($product->product_stock > 0) 
                                    <form action="{{ route('checkout-post') }}" method="post"> @csrf 
                                        <input type="hidden" name="product_id[]" value="{{ $product->id }}">
                                        <input type="hidden" name="product_qty[]" value="1">
                                        <button class="w-100">Buy Now</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                   
                
                    <!-- Big image area/canvas -->
                    
                </div>

                

            </div> {{-- Images section End --}}


            {{-- Product details start --}}
            <div class="col-md-12 col-lg-6 col-12">
                <div class="product-details-content">


                    <h3>{{$product->product_name}}</h3>
                    <div class="rating-number">
                        <div class="quick-view-rating">
                            
                            <i class="@if ($stars >= 1)fas fa-star green-star @else fas fa-star @endif" aria-hidden="true"></i>
                            <i class="@if ($stars >= 2)fas fa-star green-star @else fas fa-star @endif" aria-hidden="true"></i>
                            <i class="@if ($stars >= 3)fas fa-star green-star @else fas fa-star @endif" aria-hidden="true"></i>
                            <i class="@if ($stars >= 4)fas fa-star green-star @else fas fa-star @endif" aria-hidden="true"></i>
                            <i class="@if ($stars >= 5)fas fa-star green-star @else fas fa-star @endif" aria-hidden="true"></i>
                        </div>
                        <div class="quick-view-number">
                            <span>
                                {{$reviews->count()}} Rating/Review @if($reviews->count() > 1)(S)@endif 
                            </span>
                        </div>
                    </div>


                    {{-- Mrp - Price - Dsicount --}}
                    <div class="details-price">
                        <span class="text-muted" style="font-size: 15px;"><font class="rupees"><s>&#8377;</font> {{ moneyFormatIndia($product->product_mrp) }}</s></span>
                        <br>
                        <span><font class="rupees">&#8377;</font> {{ moneyFormatIndia($product->product_price) }} 
                            <b style="font-size: 17px; color: #388e3c; font-weight: 500;">{{ $discount }}% off</b>
                        </span>
                        @if ($product->product_stock <= 0)
                        <br>
                        <span class="text-danger">Out Of Stock</span>
                        @endif
                    </div>

                    <div class="est-delivery-date">
                        @if ($product->delivery_type == 'electronic')
                        <span>Email Delivery: 
                            <b>@if (isset($product->licenses) && count($product->licenses) > 0) Instant. @else Within 30 Mins. @endif</b>
                        </span>
                        @else
                        <span>Est. Delivery Date: 
                            <b>{{ $est_dt->format( 'dS M, Y (D)' ) }}</b>
                        </span>
                        @endif

                            {{--  Category --}}
                        <div class="product-details-cati-tag "  >
                            <ul>
                                <li >Category :</li>
                                <li><b>{{$category->category}}</b></li>
                            </ul>
                        </div>

                    </div>


                    


                    @can('Admin') 
                    <div class="row">
                        <div class="col-12 mb-1">
                            <span style="font-weight: 600; font-size: 16px;">Admin Tools</span>
                        </div>
                        <div class="col-12 mb-3">
                            <a type="button" class="btn btn-info btn-sm" data-toggle="tooltip" href="{{ route('edit-product', $product->id) }}"
                                title="Edit Product">
                                Edit Product
                                <i class="fa fa-cog" aria-hidden="true"></i>
                            </a>
                        </div>  
                    </div>
                    @endcan

                    @if (isset($affiliateLink))
                    @can('Affiliate') 
                    <div class="row">
                        <div class="col-12 mb-1">
                            <span style="font-weight: 600; font-size: 16px;">Affiliate Tools</span>
                            <p>
                                <span>Commision: 
                                    @if (isset($product->comission->comission))
                                        <span style="font-weight: 700;">{{ $product->comission->comission }}% </span>
                                    @else
                                        <span style="font-weight: 700;">Not Eligible</span>
                                    @endif
                                </span>
                            </p>
                        </div>
                        <div class="col-12 mb-3">

                            <button type="button" class="btn btn-dark btn-copy js-tooltip js-copy" data-toggle="tooltip" 
                                data-placement="top" data-copy="{{ route('ShortUrlRedirect', $affiliateLink->short_url) }}" title="Copy to clipboard">
                                Link
                                <i class="fa fa-link" aria-hidden="true"></i>
                            </button>

                            <button type="button" class="btn btn-primary btn-copy js-tooltip js-copy" data-toggle="tooltip" 
                            data-placement="top" data-copy="{{ route('ShortUrlRedirect', $affiliateLink->short_url) }}" title="Copy to clipboard">
                            <i class="fab fa-facebook-square"></i>
                            </button>

                            <button type="button" class="btn btn-success btn-copy js-tooltip js-copy" data-toggle="tooltip" 
                            data-placement="top" data-copy="{{ route('ShortUrlRedirect', $affiliateLink->short_url) }}" title="Copy to clipboard">
                            <i class="fab fa-whatsapp-square"></i>
                            </button>

                            <button type="button" class="btn btn-primary btn-copy js-tooltip js-copy" data-toggle="tooltip" 
                            data-placement="top" data-copy="{{ route('ShortUrlRedirect', $affiliateLink->short_url) }}" title="Copy to clipboard">
                            <i class="fab fa-twitter-square"></i>   
                            </button>

                        </div>  
                    </div>
                    @endcan
                    @endif
                    

                    {{-- Quick actions --}}           
                    @livewire('product-quick-actions', ['product' => $product, 'wishlisted' => $wishlisted, 'carted' => $carted, 'compared' => $compared], key($product->id))
                    

                    {{-- Top product description --}}
                    <section class="top-description">
                        <p class="top-description">{!! $product->product_description !!}</p>
                    </section>

                    

                    {{-- Category prev --}}
                    


                </div>
            </div> {{-- Product details end --}}  
        </div>
    </div>
</div> {{-- area container end --}}









{{-- Description & Specification Area  --}}
<div class="product-description-review-area pb-90">
    <div class="container-fluid" style="max-width: 1500px;">
        <div class="product-description-review text-center">

             {{-- Description & Specifications Toggle Buttons --}}
            <div class="description-review-title nav" role=tablist>
                @if ($product->product_long_description != '')
                <a class="active" href="#pro-dec" data-toggle="tab" role="tab" aria-selected="true">Description</a>
                @endif
                <a class="@if (!isset($product->product_long_description)) active @endif" href="#pro-specifications" data-toggle="tab" role="tab" aria-selected="false">Specifications</a>
            </div>


            <div class="description-review-text tab-content">
                {{-- Description --}}
                @if (isset($product->product_long_description))
                    <div class="tab-pane active show fade" id="pro-dec" role="tabpanel">
                        <p>{!! $product->product_long_description !!}</p>
                    </div>
                @endif
                
                {{-- Specifications --}}
                <div class="tab-pane @if (!isset($product->product_long_description)) show active @endif fade" id="pro-specifications" role="tabpanel">
                    
                    <div class="container">
                        <div style="text-align: center">
                            <table class="specification-table">
                                @foreach ($specifications as $specification)
                                <tr>
                                    <td style="width: 30%; background-color: #F3F3F3;">{{$specification->specification_key}}</td>
                                    <td style="width: 70%;">{{$specification->specification_value}}</td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                    

                </div>
            </div>


        </div>
    </div>
</div> {{-- Description & Specification Area container end --}}













{{-- Description & Specification Area  --}}
<div class="product-description-review-area pb-90">
    <div class="container">
        <div class="product-description-review text-center">

             {{-- Description & Specifications Toggle Buttons --}}
            <div class="description-review-title nav" role=tablist>
                <a class="active" href="#rating_Rev" data-toggle="tab" role="tab" aria-selected="false">Ratings & Reviews
                <a href="#qna" data-toggle="tab" role="tab" aria-selected="true">Questions & Answers</a>
                </a>
            </div>


            <div class="description-review-text tab-content">

                {{-- Rating Reviews Section --}}
                
                <div class="tab-pane active show fade" id="rating_Rev" role="tabpanel">
                    <div id="RatingAreaDIV">

                    <div class="wishlist-basic-padding" style="border: 1px solid #dddddd; border-radius: 2px;"> 
                        <div class="row">
                            <div class="col-3">
                                <span style="font-size: 30px; color: rgb(27, 27, 27);">
                                    {{$stars}} <i class="fa fa-star" aria-hidden="true"></i>
                                </span>
                                <br>
                                <span>
                                    {{$reviews->count()}} Rating/Review @if($reviews->count() > 1)(S)@endif 
                                </span>
                            </div>

                            <div class="col-6">
                                <div class="rating-slider-container row">
                                    <div class="col-12">

                                        <div class="row " >
                                            <div class="col-2">
                                                5 <i class="fa fa-star" aria-hidden="true"></i>
                                            </div>
        
                                            <div class="col-8">
                                                <div style="margin: auto; display: block; vertical-align: middle; padding-top: 6px;">
                                                    <div class="progress" style="height: 6px;">
                                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{$ratingPerc['fivePerc']}}%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-2">
                                                {{ moneyFormatIndia($ratingCounts['five']) }}
                                            </div>
                                        </div>

                                        <div class="row " >
                                            <div class="col-2">
                                                4 <i class="fa fa-star" aria-hidden="true"></i>
                                            </div>
        
                                            <div class="col-8">
                                                <div style="margin: auto; display: block; vertical-align: middle; padding-top: 6px;">
                                                    <div class="progress" style="height: 6px;">
                                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{$ratingPerc['fourPerc']}}%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-2">
                                                {{ moneyFormatIndia($ratingCounts['four']) }}
                                            </div>
                                        </div>

                                        <div class="row " >
                                            <div class="col-2">
                                                3 <i class="fa fa-star" aria-hidden="true"></i>
                                            </div>
        
                                            <div class="col-8">
                                                <div style="margin: auto; display: block; vertical-align: middle; padding-top: 6px;">
                                                    <div class="progress" style="height: 6px;">
                                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{$ratingPerc['threePerc']}}%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-2">
                                                {{ moneyFormatIndia($ratingCounts['three']) }}
                                            </div>
                                        </div>

                                        <div class="row " >
                                            <div class="col-2">
                                                2 <i class="fa fa-star" aria-hidden="true"></i>
                                            </div>
        
                                            <div class="col-8">
                                                <div style="margin: auto; display: block; vertical-align: middle; padding-top: 6px;">
                                                    <div class="progress" style="height: 6px;">
                                                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{$ratingPerc['twoPerc']}}%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-2">
                                                {{ moneyFormatIndia($ratingCounts['two']) }}
                                            </div>
                                        </div>

                                        <div class="row " >
                                            <div class="col-2">
                                                <span>
                                                    1 <i class="fa fa-star" aria-hidden="true"></i>
                                                </span>
                                                
                                            </div>
        
                                            <div class="col-8">
                                                <div style="margin: auto; display: block; vertical-align: middle; padding-top: 6px;">
                                                    <div class="progress" style="height: 6px;">
                                                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{$ratingPerc['onePerc']}}%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-2">
                                                {{ moneyFormatIndia($ratingCounts['one']) }}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            @if ($ordered == 1)
                            <div class="col-3">
                                <button class="btn btn-dark ReviewModalToggleBtn">@if ($reviewed == 1) Edit Review @else Rate Product @endif</button>
                            </div>
                            @endif
                            
                        </div>

                    </div>

                    <form id="ProductReviewForm" method="POST" class="d-none"> 
                        @csrf
                        <input type="hidden" name="action" value="{{route('review-submit')}}">
                    <div class="modal-content ">
                        <div class="modal-body" style="display: unset;">
                
                            <div class="mb-3">
                                <span style="font-size: 18px; font-weight: 500;">Rate This Product</span>
                            </div>
                            <input type="hidden" value="{{$product->id}}" name="product_id">
                        
                                <div class="form-field w-100">
                                    <select id="glsr-ltr" class="star-rating" name="rating" required>
                                        <option value="" disabled selected>Select a rating</option>
                                        <option value="5" @if(isset($ReviewCheck->stars) && $ReviewCheck->stars == 5) selected @endif>Fantastic</option>
                                        <option value="4" @if(isset($ReviewCheck->stars) && $ReviewCheck->stars == 4) selected @endif>Great</option>
                                        <option value="3" @if(isset($ReviewCheck->stars) && $ReviewCheck->stars == 3) selected @endif>Good</option>
                                        <option value="2" @if(isset($ReviewCheck->stars) && $ReviewCheck->stars == 2) selected @endif>Poor</option>
                                        <option value="1" @if(isset($ReviewCheck->stars) && $ReviewCheck->stars == 1) selected @endif>Terrible</option>
                                    </select>
                                </div>
                        
                            
                        </div>
                        <div style="border-top: 1px solid #dee2e6;"></div>
                        <div class="modal-body" style="display: unset;">
                            <span style="font-size: 18px; font-weight: 500;">Review This Product</span> 
                            <div class="form-group mt-3">
                            <input type="text" value="{{ $ReviewCheck->title ?? '' }}"
                                class="form-control" maxlength="50" name="title" id="title" aria-describedby="helpId" placeholder="Title (Optional)">
                            </div>
                
                            <div class="form-group">
                            <textarea maxlength="300" class="form-control" name="message" id="" rows="4" placeholder="Detailed Review...">{{ $ReviewCheck->message ?? '' }}</textarea>
                            </div>
                
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary ReviewModalToggleBtn" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                    </form>



                    @foreach ($reviews as $review)
                       @if ($review->message != '' || $review->title != '')
                            <div class="wishlist-basic-padding" style="border: 1px solid #dddddd; border-radius: 2px; border-top: 0;">
                                <div class="row">
                                    <button type="button" class="btn btn-dark btn-sm">{{$review->stars}} <span><i class="fa fa-star" aria-hidden="true"></i></span></button>
                                    <span style="padding-left: 12px; padding-top: 3px; font-size: 14px; color: #212121; font-weight: 500;">{{ $review->title ?? '' }}</span>
                                </div>
                                <div class="row">
                                    <span style="margin: 12px 0;">{{ $review->message }}</span>
                                </div>
                                <div class="row">
                                    <span style="margin: 12px 0;">
                                        {{ $review->user->name }} <img loading="lazy" width="14" src="{{asset('img/svg/verified-tick.svg')}}" alt=""> (Buyer), {{ HowMuchOldDate($review->created_at, 'days') }} ago
                                    </span>
                                </div>
                            </div>        
                        @endif
                    @endforeach

                    @if ($reviews->count() >= 1)
                        <div class="view-more-continer mt-3" >
                            <a href="{{route('all-product-reviews', $product->id)}}">
                                <span style="color: #0066c0;" class="hover-blue"> All {{ App\Models\ProductReview::where('product_id', $product->id)->count() }} Reviews <i class="fa fa-arrow-right" aria-hidden="true"></i></span>
                            </a>
                        </div>
                    @endif
                    

                </div>
            </div>



                    {{-- Qna Section --}}
                    <div class="tab-pane fade" id="qna" role="tabpanel">

                        <div class="" style="border: 1px solid #dddddd; border-radius: 2px;">

                                <div style="border-bottom: 1px solid #dddddd;">
                                    <div class="row" >
                                        <div class="w-100" style="padding: 24px 15px 0px 15px; ">
                                            <div class="col-9">
                                            
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                        <span class="input-group-text" id="searchPre">
                                                            <i class="fa fa-search" aria-hidden="true"></i>
                                                            </span>
                                                        </div>
                                                        <input type="text" id="review_search" class="form-control" placeholder="Search for answers..." aria-label="Username" aria-describedby="basic-addon1">
                                                    </div>
                                            </div>
                                            <div class="col-3">
                                            
                                                    <div class="form-group">
                                                        <select onchange="fetchQnas('new')" class="form-control" name="" id="sort_by" style="font-size: 14px;">
                                                        <option value="Not Selected" selected disabled>Sort By</option>
                                                        <option value="Random">Default</option>
                                                        <option value="Newest First">Newest First</option>
                                                        <option value="Oldest First">Oldest First</option>
                                                        </select>
                                                    </div>
                                            
                                            </div>
                                        </div>
                                    </div>
                                </div>            
                                  
                                    <div class="">
                                        <div class="w-100" style="padding-bottom: 22px;" >
                                            @csrf
                                            <input type="hidden" id="product_id" value="{{ $product->id }}">
                                            <input type="hidden" id="domain" value="{{ url('/') }}">
                                            <input type="hidden" id="reviews_form_action" value="{{ route('get-product-qnas') }}">
                                            <div id="ShowReviewsArea" class="mt-3 w-100" style="padding-bottom: 30px;
                                            border-bottom: 1px solid #dddddd;">

                                                <div class="div-loader" id="reviewsDivLoader">
                                                    <div style="text-align: center;">
                                                        <div class="spinner-border text-dark" role="status" style="width: 90px; height: 90px; ">
                                                            <span class="sr-only">Loading...</span>
                                                        </div>
                                                        <br>
                                                        <div class="mt-3">
                                                            <span>Loading...</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                                <div class="w-100 loadMoreBtnContainer text-center d-none">
                                                    <div class="mt-3" style="text-align: center;">
                                                    <a class="cursor-pointer" style="color: #0066c0;"  id="loadMoreQnas">Load More...</a>
                                                    </div>
                                                </div>

                                                <div class="w-100 text-center mt-3 mb-3"    >
                                                    <div class="mb-3 mt-3">Don't see the answer you're looking for? </div>
                                                    <button class="btn btn-dark btn-sm" id="PostQuestionBtn">Post Question</button>
                                                </div>
                                        </div>
                                    </div>
                        </div> 
                    </div>
            </div>
        </div>
    </div>
</div> {{-- Description & Specification Area container end --}}







<!-- Related products area start -->
<div class="product-area pb-95">
    <div class="electronic-banner-area">
        <div class="section-title-3 text-center mb-50">
            <h2>Related products</h2>
        </div>
        <div class="product-style">
            <div class="related-product-active owl-carousel">
                @if (isset($RelatedProducts))
                    @foreach ($RelatedProducts as $RelatedProduct)
                        @if ($RelatedProduct->id != $product->id)
                            @livewire('related-product', ['product' => $RelatedProduct], key($RelatedProduct->id))
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
<!-- Related products area end -->

@endsection



@section('bottom-js')


    
<script>
    $(document).ready(function(){
        $('.big_img').imagezoomsl({
            cursorshade: true,
            zindex: 11,
            magnifycursor:'zoom-in',
            zoomrange: [2, 10],
            scrollspeedanimate: 5,
            zoomspeedanimate: 1,
            loopspeedanimate: 1,  
            magnifierspeedanimate: 350,
            magnifiersize: ['43%', '75vh'],
            leftoffset:  15, 						// îòñòóï ñëåâà îò tmb êàðòèíêè
            rightoffset: 15, 	
            magnifierborder: 'no-border',   
        });
    });
</script>


<script>

$('document').ready( function (){
    fetchQnas('new')
})


//setup before functions
var typingTimer;                //timer identifier
var doneTypingInterval = 1500;  //time in ms, 5 second for example
var $input = $('#review_search');

//on keyup, start the countdown
$input.on('keyup', function () {
    $('#searchPre').html(`<i class="fa fa-spinner fa-spin"></i>`)
    clearTimeout(typingTimer);
    typingTimer = setTimeout(doneTyping, doneTypingInterval);
});

//on keydown, clear the countdown 
$input.on('keydown', function () {
  clearTimeout(typingTimer);
});

//user is "finished typing," do something
function doneTyping () {
    $('#searchPre').html(`<i class="fa fa-search" aria-hidden="true"></i>`)
    $('#searchPre').removeClass('searchLoading')
    fetchQnas('new')
}




</script>

@endsection