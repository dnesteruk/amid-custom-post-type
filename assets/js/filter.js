jQuery(document).ready(function ($) {
    /**
     * Retrieve posts
     */
    function get_posts($params) {

        let $container = $('#container-async');
        let $content = $container.find('.content');
        let $status = $container.find('.status');

        $status.text('Loading posts ...');

        //console.log($params)

        /**
         * Do AJAX
         */
        $.ajax({
            url: amid_cpt.ajax_url,
            data: {
                action: 'amid_cpt_filter_query_posts',
                nonce: amid_cpt.nonce,
                params: $params,
            },
            type: 'post',
            dataType: 'json',
            success: function (data, textStatus, XMLHttpRequest) {

                if (data.status === 200) {
                    //console.log(data.next)
                    $content.html(data.content);
                } else if (data.status === 201) {
                    $content.html(data.message);
                } else {
                    $status.html(data.message);
                }
            },
            error: function (MLHttpRequest, textStatus, errorThrown) {

                $status.html(textStatus);
            },
            complete: function (data, textStatus) {

                let msg = textStatus;

                if (textStatus === 'success') {
                    msg = data.responseJSON.message;
                }

                $status.html(msg);
            }
        });
    }

    /**
     * Bind get_posts to tag cloud and navigation
     */
    $('.amid-cpt-container-filter').on('click', 'a[data-filter], .page-numbers a', function (event) {
        if (event.preventDefault) {
            event.preventDefault();
        }

        let $this = $(this);

        /**
         * Set filter active
         */

        let $page
        let $name;
        if ($this.data('filter')) {
            $page = 1;

            /**
             * If all terms, then deactivate all other
             */
            if ($this.data('term') === 'all-terms') {
                $this.closest('ul').find('.active').removeClass('active');
            } else {
                $('a[data-term="all-terms"]').parent('li').removeClass('active');
            }

            // Toggle current active
            $this.parent('li').toggleClass('active');

            /**
             * Get All Active Terms
             */
            $active = {};
            $name = [];
            $terms = $this.closest('ul').find('.active');

            if ($terms.length) {
                $.each($terms, function (index, term) {

                    let $a = $(term).find('a');
                    let $tax = $a.data('filter');
                    let $slug = $a.data('term');

                    if ($tax in $active) {
                        $active[$tax].push($slug);
                    } else {
                        $active[$tax] = [];
                        $active[$tax].push($slug);
                    }

                    $name.push($a.text().trim())
                });
            }

        } else {
            /**
             * Pagination
             */
            $page = parseInt($this.attr('href').replace(/\D/g, ''));
            $this = $('.wrap-term-filter .active a');
        }

        let $params = {
            'page': $page,
            'terms': $active,
            'names': $name,
            'qty': $this.closest('#container-async').data('paged'),
        };

        // Run query
        get_posts($params);
    });

    /**
     * Show all posts on page load
     */
    $('a[data-term="all-terms"]').trigger('click');
});
