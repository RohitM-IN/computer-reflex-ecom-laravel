function clickEl(el) {
  $(el).click();
}

Toast.setTheme(TOAST_THEME.DARK);
Toast.setPlacement(TOAST_PLACEMENT.BOTTOM_RIGHT);
Toast.setMaxCount(10);


function cartEvent(data) {
  if (data.action == 'cartAdded') {
      $('.cart-btn-a'+data.product_id).addClass('cart-btn-active');
      $('.cart-btn-b'+data.product_id).addClass('cart-btn-active');
      $('.cart-btn-c'+data.product_id).addClass('cart-btn-active').html(`REMOVE FROM CART`);
      $('.cart-btn-d'+data.product_id).addClass('cart-btn-active').html(`<i class="fas fa-minus-circle"></i> Remove From Cart`);
      Toast.create(`Added To Cart`, data.product_name, TOAST_STATUS.SUCCESS, 5000);
  }
  else if (data.action == 'cartRemoved') {
      $('.cart-btn-a'+data.product_id).removeClass('cart-btn-active');
      $('.cart-btn-b'+data.product_id).removeClass('cart-btn-active');
      $('.cart-btn-c'+data.product_id).removeClass('cart-btn-active').html(`ADD FROM CART`);
      $('.cart-btn-d'+data.product_id).removeClass('cart-btn-active').html(`<i class="fad fa-cart-plus"></i> Add To Cart`);
      Toast.create(`Removed From Cart`, data.product_name, TOAST_STATUS.DANGER, 5000);
  }
}


// Wishlist Toggle Alert And Change Btn
Livewire.on('wishlistAdded', data => {
  $('.wishlist-btn-a'+data['product_id']).addClass('wishlist-btn-active');
  $('.wishlist-btn-b'+data['product_id']).addClass('wishlist-btn-active');
  $('.wishlist-btn-c'+data['product_id']).addClass('btn-wishlisted').removeClass('btn-not-wishlisted');
  Toast.create("Added To Wishlist", data['product_name'], TOAST_STATUS.SUCCESS, 5000);
});

Livewire.on('wishlistRemoved', data => {
  $('.wishlist-btn-a'+data['product_id']).removeClass('wishlist-btn-active');
  $('.wishlist-btn-b'+data['product_id']).removeClass('wishlist-btn-active');
  $('.wishlist-btn-c'+data['product_id']).removeClass('btn-wishlisted').addClass('btn-not-wishlisted');
  Toast.create("Removed From Wishlist", data['product_name'], TOAST_STATUS.DANGER, 5000);
});


// Compare Toggle Alert And Change Btn
Livewire.on('compareAdded', data => {
  $('.compare-btn-a'+data['product_id']).addClass('compare-btn-active');
  $('.compare-btn-b'+data['product_id']).addClass('compare-btn-active');
  $('.compare-btn-c'+data['product_id']).addClass('btn-danger').removeClass('btn-info');
  Toast.create("Added To Compare", data['product_name'], TOAST_STATUS.SUCCESS, 5000);
});

Livewire.on('compareRemoved', data => {
  $('.compare-btn-a'+data['product_id']).removeClass('compare-btn-active');
  $('.compare-btn-b'+data['product_id']).removeClass('compare-btn-active');
  $('.compare-btn-c'+data['product_id']).removeClass('btn-danger').addClass('btn-info');
  Toast.create("Removed From Compare", data['product_name'], TOAST_STATUS.DANGER, 5000);
});

Livewire.on('modal', data => {
  $(data['el']).modal(data['action']);
});


Livewire.on('toastAlert', data => {
  console.log('Toast notification');
  if (data['type'] == 'SUCCESS') {
    var type = TOAST_STATUS.SUCCESS;
  }
  if (data['type'] == 'DANGER') {
    var type = TOAST_STATUS.DANGER;
  }

  Toast.create(data['title'], data['caption'], type, 5000);
});


// Toast.create(title, message, status = 0, timeout = 0)
// Toast.setTheme(TOAST_THEME.DARK);
// Toast.create("Title", "Toast Message", TOAST_STATUS.SUCCESS, 0);





  $(".collapse-btn").on("click",function() {
    this.classList.toggle("on");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight){
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    }
  });

  $('input[name="cancel_review"]').on('change', function () {
    if ($(this).val() == 'decline') {
      $('.reviewComment').removeClass('d-none');
      $('.reviewRefund').addClass('d-none');
      $('#review_comment').attr('required', true);
    } else {
      
      $('.reviewRefund').removeClass('d-none');
      $('.reviewComment').addClass('d-none');
      $('#review_comment').attr('required', false);
    }
  })
  

  
  initProductCarousel()
  function initProductCarousel() {
    $('.bbb_viewed_slider').owlCarousel({
      loop:false,
      rewind:true,
      margin:30,
      autoplay:true,
      autoplayHoverPause: true,
      autoplayTimeout:8000,
      nav:false,
      lazyLoad:false,
      dots:false,
      responsive:
        {
          0:{items:2},
          575:{items:2},
          768:{items:3},
          991:{items:4},
          1199:{items:6}
        }
    });
  }



function PrevCarousel(section_id) {
  $('.owl-carousel-'+section_id).trigger('prev.owl.carousel')
}
function NextCarousel(section_id) {
  $('.owl-carousel-'+section_id).trigger('next.owl.carousel')
}


$('.images-menu').on('mouseover click', function () {
  imageUrl = $(this).css('background-image').replace(/^url\(['"]?/,'').replace(/['"]?\)$/,'');
  $('#big_img').attr('src', imageUrl)
})


$('#help_topic').on('change', function () {
  if ($('#help_topic').val() == 'Order Related') {
    $('#subOptionDiv').html($('#forOrders').html());
  } 
  else if ($('#help_topic').val() == 'Return/Refund') {
    $('#subOptionDiv').html($('#forOrders').html());
  } 
  else {
    $('#subOptionDiv').html('');
  }
})





$('#filter_form').off('submit').on('submit', function (e) {
    e.preventDefault()
    var formGetURL = $('#filter_form').attr('action') + "?" + $('#filter_form').serialize();
    $.get(formGetURL, function(data) {
      var newContent = $(data).find('#RowDiv').children();
      $('#RowDiv').empty().append(newContent);
      history.pushState({page: null}, null, formGetURL);
    });
})



$('#sort_by_select').one('change', function () {
  $('#filter_form').find('input[name="sort_by"]').val($(this).val());
  $('#SearchSortModal').modal('toggle');
  $('#filter_form').submit();
})



// $('.PaginationBtn').off('click').on('click', function (e) {
//   e.preventDefault();

//   var formGetURL = $(this).attr('href');

//   $.get(formGetURL, function(data) {
//     var RowDiv = $(data).find('#RowDiv').children();
//     $('#RowDiv').empty().append(RowDiv);

//     var PadginationContainer = $(data).find('#PadginationContainer').children();
//     $('#PadginationContainer').empty().append(PadginationContainer);

//     $.getScript('/js/main.js'); 
    
//     history.pushState({page: null}, null, formGetURL);

//   });

// })





$('#CategorySelect').on('change', function () {
  $('#CategoryInput').val($(this).val())
})



$('.ReviewModalToggleBtn').on('click', function () {
    if ($('#ProductReviewForm').hasClass('d-none')) {
      $('#ProductReviewForm').removeClass('d-none')
    } else {
      $('#ProductReviewForm').addClass('d-none')
    }
})

if ($('.star-rating').length) {
  var destroyed = false;
  var starratingPrebuilt = new StarRating('.star-rating-prebuilt', {
      prebuilt: true,
      maxStars: 5,
  });
  var starrating = new StarRating('.star-rating', {
      stars: function (el, item, index) {
          el.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><rect class="gl-star-full" width="19" height="19" x="2.5" y="2.5"/><polygon fill="#FFF" points="12 5.375 13.646 10.417 19 10.417 14.665 13.556 16.313 18.625 11.995 15.476 7.688 18.583 9.333 13.542 5 10.417 10.354 10.417"/></svg>';
      },
  });
  var starratingOld = new StarRating('.star-rating-old');

}





$('#ProductReviewForm').on('submit', function (e) {
    e.preventDefault()
    var _token      = $('#ProductReviewForm').find('input[name="_token"]').val()
    var product_id  = $('#ProductReviewForm').find('input[name="product_id"]').val()
    var rating      = $('#ProductReviewForm').find('select[name="rating"]').val()
    var title       = $('#ProductReviewForm').find('input[name="title"]').val()
    var message     = $('#ProductReviewForm').find('textarea[name="message"]').val()
    var action     = $('#ProductReviewForm').find('input[name="action"]').val()

    $.ajax({
      url: action,
      method: 'POST',
      data: {
          '_token' : _token,
          'product_id' : product_id,
          'rating' : rating,
          'title' : title,
          'message' : message,
      },
      async: false,
      success: function (data) {

          if (data.status == 200) {
            $(".bootstrap-growl").remove();
            $.bootstrapGrowl(data.message, {
                type: "success",
                offset: {from:"bottom", amount: 100},
                align: 'center',
                allow_dismis: true,
                stack_spacing: 10,
            })  
              
            $('#RatingAreaDIV').load(window.location.href+" #RatingAreaDIV", function () {
              $.getScript('/js/main.js'); 
            })

          }
          if (data.status == 210) {
            $(".bootstrap-growl").remove();
            $.bootstrapGrowl(data.message, {
                type: "success",
                offset: {from:"bottom", amount: 100},
                align: 'center',
                allow_dismis: true,
                stack_spacing: 10,
            })
            fetchReviews('new')
            $('#RatingAreaDIV').load(window.location.href+" #RatingAreaDIV", function () {
              $.getScript('/js/main.js'); 
            })
             
          }
      }
  })

})




// Show Reviews on dedicated reviews page Start
function fetchReviews(type) {
  if (type == 'new') {
    $('#reviewsDivLoader').removeClass('d-none')
    $('.reviewItem').remove()
  }
  var _token              = $('input[name="_token"]').val()
  var reviews_form_action = $('#reviews_form_action').val()
  var product_id          = $('#product_id').val()
  var review_search       = $('#review_search').val()
  var sort_by             = $('#sort_by').val()
  var skip_count          = $('.reviewCount').length
  var domain              = $('#domain').val()
  $.ajax({
    url: reviews_form_action,
    method: 'POST',
    data: {
        '_token'        : _token,
        'product_id'    : product_id,
        'review_search' : review_search,
        'sort_by'       : sort_by,
        'skip_count'    : skip_count,
    },
    async: false,
    success: function (data) {
      if (data.status == 200) {
        $('#reviewsDivLoader').addClass('d-none')
        if (data.reviewsCount == 0) {
            $('#ShowReviewsArea').html(
              `
              <div class="reviewItem">
                <div class="w-100 prod-back-div mt-4" style="height: 146px; background-image: url('${domain}/img/svg/notify.svg');"></div>
                <div class="mt-3" style="text-align: center;">
                    <span style="font-size: 18px;">No Review Found</span>
                </div>
              </div>
              `)
        } 
        else {
          data.reviews.forEach(review => {
            $('#ShowReviewsArea').append(`
            <div class="reviewItem reviewCount" style="border: 1px solid #dddddd; border-radius: 2px; border-left: 0; border-right: 0;">
              <div class="wishlist-basic-padding" >
                  <div class="row">
                      <button type="button" class="btn btn-dark btn-sm">
                          
                          ${review.stars} <span><i class="fa fa-star" aria-hidden="true"></i></span>
                      </button>
                      <span style="padding-left: 12px; padding-top: 3px; font-size: 14px; color: #212121; font-weight: 500;">${review.title}</span>
                  </div>
                  <div class="row">
                      <span style="margin: 12px 0;">
                      ${review.message}
                      </span>
                  </div>
    
                  <div class="row">
                      <span style="margin: 12px 0;">
                          ${review.user.name} <img width="14" src="${domain}/img/svg/verified-tick.svg" alt=""> (Buyer), ${review.days_ago}
                      </span>
                  </div>

              </div>
            </div>
              `)
        });
          if (data.reviewsCount != $('.reviewCount').length && data.reviewsCount != 0) {
            $('.loadMoreBtnContainer').removeClass('d-none')
          } else {
            $('.loadMoreBtnContainer').addClass('d-none')
          }
        }
      }
    }
  })
}

$('#loadMoreReviews').on('click', function () {
  fetchReviews()
})
// Show Reviews on dedicated reviews page End
















// Fetch QNA Start
function fetchQnas(type) {
 
  if (type == 'new') {
    $('#reviewsDivLoader').removeClass('d-none')
    $('.reviewItem').remove()
  }
  var _token              = $('input[name="_token"]').val()
  var reviews_form_action = $('#reviews_form_action').val()
  var product_id          = $('#product_id').val()
  var review_search       = $('#review_search').val()
  var sort_by             = $('#sort_by').val()
  var skip_count          = $('.reviewCount').length
  var domain              = $('#domain').val()

  $.ajax({
    url: reviews_form_action,
    method: 'POST',
    data: {
        '_token'        : _token,
        'product_id'    : product_id,
        'review_search' : review_search,
        'sort_by'       : sort_by,
        'skip_count'    : skip_count,
    },
    async: false,
    success: function (data) {
      if (data.status == 200) {
        $('#reviewsDivLoader').addClass('d-none')
        if (data.qnasCount == 0) {
            $('#ShowReviewsArea').html(
              `
              <div class="reviewItem">
                <div class="w-100 prod-back-div mt-4" style="height: 146px; background-image: url('${domain}/img/svg/notify.svg');"></div>
                <div class="mt-3" style="text-align: center;">
                    <span style="font-size: 18px;">No QnA Found</span>
                </div>
              </div>
              `)
        } 
        else {
          let content;
          for (let i = 0; i < data.qnas.length; i++) {
            let qnas = data.qnas[i]
            content = `
            <div class="reviewItem reviewCount" style="border-bottom: 1px solid #dddddd; ">
              
            <div style="text-align: left; padding: 10px 24px;">
                <div class="">
                  <span style="color: black; font-weight: 600;">
                    Q: ${qnas.question}
                  </span>
                `
                if (qnas.answerable != false) {
                  content += `
                    <span style="color: black; font-weight: 600;" class="float-right">
                      <button class="btn btn-sm btn-dark" id="AddAnswerButton" question_id="${qnas.id}">Add Answer</button>
                    </span>
                  `
                }

                content += `</div>
                  </div>`
                            
              
              if (qnas.answers.length < 1) {
                content += `
                  <div style="text-align: left; padding: 10px 24px;">
                    <div class="">
                      <span style="">No Answers Yet</span>
                    </div>
                  </div>
                `
              }

              else {
                for (let index = 0; index < data.qnas[i].answers.length; index++) {
                
                  let answers = qnas.answers[index]
  
                    content += `
                    <div style="text-align: left; padding: 10px 24px;">
                      <div class="">
                        <span style="">
                          A: ${answers.answer}
                        </span>
                      </div>
                      <div class="mt-1">
                        <span style="">
                        ${answers.user.name} <img width="14" src="${domain}/img/svg/verified-tick.svg" alt=""> (Buyer), 
                        </span>
                      </div>
                    </div> 
                      `
                }
              }
          
              content += `</div>`;
            }
            $('#ShowReviewsArea').append(content);
          
       
          

            if (data.qnasCount != $('.reviewCount').length && data.qnasCount != 0) {
            $('.loadMoreBtnContainer').removeClass('d-none')
          } else {
            $('.loadMoreBtnContainer').addClass('d-none')
          }
        }


      }
    }
  })
}

$('#loadMoreQnas').on('click', function () {
  fetchQnas()
})



// Open Add Answer Modal with prefilled Question and Existing Answer for same user if exists.
$('#ShowReviewsArea').on('click', '#AddAnswerButton', function () {
  var question_id = $(this).attr('question_id')
  var fetchDataURL = $('#SubmitAnswerForm').find('input[name="fetchData"]').val();
  var question_id_form_field = $('#SubmitAnswerForm').find('input[name="question_id"]').val();

  if (question_id_form_field == question_id) {
    $('#AddAnswerModal').modal('toggle')
    return;
  }

  $.ajax({
    url: fetchDataURL,
    method: "GET",
    data: {
      question_id : question_id,
    },
    success: function (data) {
      if (data.status == 200) {
        $('#SubmitAnswerForm').find('input[name="question_id"]').val(data.qna.id);
        $('#SubmitAnswerForm').find('input[name="question"]').val(data.qna.question);
        if (answer != false) {
          $('#SubmitAnswerForm').find('input[name="answer"]').val(data.answer.answer);
        }
        $('#AddAnswerModal').modal('toggle')
      }

    }
  })

})


$('#SubmitAnswerForm').on('submit', function (e) {
  e.preventDefault()
  var action = $(this).attr('action');
  var _token = $(this).find('input[name="_token"]').val();
  var question_id = $(this).find('input[name="question_id"]').val();
  var answer = $(this).find('input[name="answer"]').val();

  $.ajax({
    url: action,
    method: "POST",
    data: {
      _token: _token,
      question_id: question_id,
      answer: answer,
    },
    success: function (data) {
      if (data.status == 200) {
        $('#review_search').val($('#SubmitAnswerForm').find('input[name="question"]').val());
        fetchQnas('new');
        $('#AddAnswerModal').modal('toggle');
      }
    }
  })

})
// Fetch QNA End





$('#PostQuestionBtn').on('click', function () {
  $('#ask_question').val($('#review_search').val())
  $('#PostQuestionModal').modal('toggle');
})


$('#QuestionSubmitForm').on('submit', function (e) {
  e.preventDefault()
  var action = $('#QuestionSubmitForm').attr('action')
  var _token = $('#QuestionSubmitForm').find('input[name="_token"]').val()
  var question = $('#QuestionSubmitForm').find('input[name="question"]').val()
  var product_id = $('#QuestionSubmitForm').find('input[name="product_id"]').val()

  $.ajax({
    url: action,
    method: 'POST',
    data: {
        '_token'        : _token,
        'product_id'    : product_id,
        'question'      : question,
    },
    async: false,
    success: function (data) {
      if (data.status == 200) {
        $('#PostQuestionModal').modal('toggle');
        $(".bootstrap-growl").remove();
        $('#review_search').val(question); 
        fetchQnas('new');
        $.bootstrapGrowl("Question Added.", {
            type: "success",
            offset: {from:"bottom", amount: 50},
            align: 'center',
            allow_dismis: true,
            stack_spacing: 10,
        })
      }
    }
  })

})









$('#multi-item-carousel').carousel({
    interval: 10000
  })
  
  $('.carousel .carousel-item').each(function(){
      var minPerSlide = 4;
      var next = $(this).next();
      if (!next.length) {
      next = $(this).siblings(':first');
      }
      next.children(':first-child').clone().appendTo($(this));
      
      for (var i=0;i<minPerSlide;i++) {
          next=next.next();
          if (!next.length) {
              next = $(this).siblings(':first');
            }
          
          next.children(':first-child').clone().appendTo($(this));
        }
  });







// COPY TO CLIPBOARD
// Attempts to use .execCommand('copy') on a created text field
// Falls back to a selectable alert if not supported
// Attempts to display status in Bootstrap tooltip
// ------------------------------------------------------------------------------

function copyToClipboard(text, el) {
  var copyTest = document.queryCommandSupported('copy');
  var elOriginalText = el.attr('data-original-title');

  if (copyTest === true) {
    var copyTextArea = document.createElement("textarea");
    copyTextArea.value = text;
    document.body.appendChild(copyTextArea);
    copyTextArea.select();
    try {
      var successful = document.execCommand('copy');
      var msg = successful ? 'Copied!' : 'Whoops, not copied!';
      el.attr('data-original-title', msg).tooltip('show');
    } catch (err) {
      console.log('Oops, unable to copy');
    }
    document.body.removeChild(copyTextArea);
    el.attr('data-original-title', elOriginalText);
  } else {
    // Fallback if browser doesn't support .execCommand('copy')
    window.prompt("Copy to clipboard: Ctrl+C or Command+C, Enter", text);
  }
}

  // Initialize
  // ---------------------------------------------------------------------
  // Tooltips
  // Requires Bootstrap 3 for functionality
  $('.js-tooltip').tooltip();

  // Copy to clipboard
  // Grab any text in the attribute 'data-copy' and pass it to the 
  // copy function
  $('.js-copy').click(function() {
    var text = $(this).attr('data-copy');
    var el = $(this);
    copyToClipboard(text, el);
  });

// Copy To CLipboard End









Livewire.on('cropperInit', e => {
  console.log('CropperJS Initiated. 🟢',);
  var wireId = e['wireId'];
  var image = $(`#bannerImgCrop-${e['id']}`);
  console.log(e['aspectRatio']);
  image.cropper('destroy').attr('src', e['imageUrl']).cropper({
      aspectRatio: e['aspectRatio'],
      autoCropArea: 1,
      viewMode: 1,
      crop (e) {
          var component = Livewire.find(wireId);
          component.set('x', e.detail.x, true);
          component.set('y', e.detail.y, true);
          component.set('width', e.detail.width, true);
          component.set('height', e.detail.height, true);
      }
  });
});





AdminProductTableInit()
function AdminProductTableInit() {
  var table = $('.AdminProductsTable');
  $('.AdminProductsTable').DataTable({
    processing: true,
    serverSide: true,
    ajax:{
        url: table.data('url')
    },
    columns: [
        {
            data: 'id',
            name: 'id',
        },
        {
            data: 'product_name',
            name: 'product_name',
        },
        {
            data: 'product_brand',
            name: 'product_brand',
        },
        {
            data: 'product_mrp_custom',
            name: 'product_mrp_custom',
        },
        {
            data: 'product_price_custom',
            name: 'product_price_custom',
        },
        {
            data: 'stock',
            name: 'stock',
        },
        {
            data: 'product_status',
            name: 'product_status',
        },
        {
            data: 'action',
            name: 'action',
            orderable: false,
        },
    ]
});
  
}
