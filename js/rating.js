// @prepros-prepend ../node_modules/featherlight/src/featherlight.js

(function (window, document, $) {
  $(document).ready(function () {
    var $btnRateProducts = $('.btn-rate-product');
    if ($btnRateProducts.length < 1) {
      return;
    }

    let currentProduct = null;
    let loading = false;

    // handle opening rating system box
    $(document).on('click', '.btn-rate-product', function () {
      const product = JSON.parse($(this).attr('data-product'));
      // console.log({product});
      $.featherlight($('.rating-system-content'), {
        beforeOpen: function () {
          $('.rsc__product-image').html(`<img src="${product.image}" alt="">`);
          $('.rsc__product-title').html(product.title);
        },
        afterContent: function () {
          currentProduct = product;

          // console.log('afterContent');
          let content = this.$content.html();
          content = content.replace(/__FEATHER_ID__/g, `feather_${this.id}`);
          this.$content.html(content);
        },
        afterClose: function () {
          currentProduct = null;
        },
      });
    });

    // enable the submit rating button when user selected rating
    $(document).on('click', '.featherlight .rsc__rating label', function () {
      $('.featherlight .btn-submit-rating').removeAttr('disabled');
    });

    // handle rating submission
    $(document).on('click', '.btn-submit-rating', function () {
      if (loading || !currentProduct) {
        return;
      }
      loading = true;

      const $btn = $(this);
      $btn.addClass('loading');
      $btn.next('.rsc__error').text('');

      const rating = $('.featherlight input[name="rating"]:checked').val();

      $.ajax({
        type: 'POST',
        url: '/wp-admin/admin-ajax.php',
        data: {
          action: 'apd_rate_product',
          product_id: currentProduct.id,
          rating,
        },
        success: function (response) {
          if (!response.success) {
            $btn.next('.rsc__error').text('Something goes wrong. Please try again later.');
          } else {
            // display average rating and thank you message
            $('.featherlight .rsc__average-rating__rating').attr('data-rating', response.data.rounded_average_rating);
            $('.featherlight .rsc__average-rating__rating span:first-child').text(response.data.average_rating);
            $('.featherlight .rsc__average-rating__help-text span:first-child').text(response.data.rounded_average_rating_text);
            $('.featherlight .rsc__total-count span').text(response.data.rating_count);
            $('.featherlight .rating-system-content').addClass('thank-you');

            var ratingDetailsTemplateHtml = $('.rsc-templates .rsc__average-rating-short-template').html();
            ratingDetailsTemplateHtml = ratingDetailsTemplateHtml.replace(/__ROUNDED_AVERAGE_RATING__/g, response.data.rounded_average_rating);
            ratingDetailsTemplateHtml = ratingDetailsTemplateHtml.replace(/__AVERAGE_RATING__/g, response.data.average_rating);

            // add rating data to the product after successful rating
            $(`.btn-rate-product[data-product_id="${currentProduct.id}"]`).each(function () {
              $(this).after(ratingDetailsTemplateHtml);
              $(this).remove();
            });

            // increase rewards amount
            const rewardsAmount = parseFloat($('.wallet-info-holder .value').text().replace(/[^0-9.]*/g, '')) || 0;
            $('.wallet-info-holder .value').text(`${(rewardsAmount + 5.00).toFixed(2)}`);
          }
        },
        error: function (error) {
        },
        complete: function () {
          loading = false;
          $btn.removeClass('loading');
        }
      });
    });
  });
})(window, document, jQuery);

