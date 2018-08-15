const Comment = function(options) {
    const commentWrapper = '.comment-wrapper',
        replyFormSelector = '.reply-form',
        testimonalsItemSelector = '.testimonials-item',
        commentFormSelector = '.comment-form',
        $commentList = $('#comment-list'),
        loadMoreSelector = '#load-more-comments';

    var stateHolder = {
            voteRequestFree: true,
            addCommentRequestFree: true
        },
        pageOptions = $.extend(true, {
            validationUrl: null,
            loadMoreUrl: null,
            voteUrl: null,
            cid: 0,
            treesLimit: null,
            addReloadUrl: null
        }, options);

    function updateCommentsTree(template, dataTreeId) {
        $.ajax({
            url: pageOptions.addReloadUrl,
            method: 'POST',
            data: {template: template, treeId: dataTreeId},
            dataType: 'json',
            success: function(response) {
                if(response) {
                    if(typeof response.items !== 'undefined') {
                        var $commentItem = $(testimonalsItemSelector + '[data-tree-id="' + dataTreeId + '"]');

                        $commentItem.first().closest(commentWrapper).replaceWith('<div id="treeReload"></div>');
                        $commentItem.each(function() {
                            $(this).closest(commentWrapper).remove();
                        });

                        $('#treeReload').replaceWith(response.items);
                    }
                }
            }
        });
    }

    $('body').on('beforeSubmit', 'form.comment-form', function() {
        if(!stateHolder.addCommentRequestFree) {
            return false;
        }

        const $form = $(this),
            isReplyForm = $form.closest('.add-testimonials').hasClass('reply'),
            formPreloaderSelector = '.form-preloader';

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serializeArray(),
            dataType: 'json',
            beforeSend: function() {
                stateHolder.addCommentRequestFree = false;
                $form.find(formPreloaderSelector).text('...')
            },
            success: function(response) {
                if(response) {
                    if(!isReplyForm) {
                        $commentList.prepend($(response).filter(commentWrapper));
                    } else {
                        var template = $(loadMoreSelector).attr('data-t'),
                            comment = $(response).filter(commentWrapper),
                            dataTreeId = comment.find(testimonalsItemSelector).data('tree-id');

                        updateCommentsTree(template, dataTreeId);
                    }
                }

                $form.find(formPreloaderSelector).text('');
                $form.find('textarea').val('');
                $form.find('.form-group').removeClass('has-success has-error');
                $form.find('button').blur();

                stateHolder.addCommentRequestFree = true;
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $form.find(formPreloaderSelector).text('');

                stateHolder.addCommentRequestFree = true;
            }
        });

        return false;
    })
        .on('click', loadMoreSelector, function(e) {
            e.preventDefault();

            const $loadMore = $(this),
                template = $loadMore.attr('data-t'),
                treeId = parseInt($loadMore.attr('data-tree-id'));

            $.post(
                pageOptions.loadMoreUrl,
                {
                    template: template, treeId: treeId, _csrf: LandingCore.getCsrfToken()
                },
                function(response) {
                    if(typeof response.ids !== 'undefined') {
                        var idsSelectors = [];
                        for(var i in response.ids) {
                            idsSelectors.push('.comment-wrapper[data-id=' + response.ids[i] + ']');
                        }

                        if(idsSelectors.length) {
                            $(idsSelectors.join(', ')).remove();
                        }
                    }

                    if(typeof response.items !== 'undefined') {
                        $commentList.append(response.items);
                    }

                    if(typeof response.treesCount !== 'undefined' &&
                        response.items !== 'undefined' && response.items) {
                        if(response.treesCount >= pageOptions.treesLimit) {
                            $loadMore.attr('data-tree-id', response.treeId);
                        }
                        else {
                            $loadMore.remove();
                        }
                    }
                    else {
                        $loadMore.remove();
                    }
                }, 'json');

            $loadMore.blur();
        })
        .on('click', '.testimonials-list .reply-to', function(e) {
            e.preventDefault();

            $('.testimonials-list .add-testimonials.reply').each(function() {
                $(this).find('.cancel-reply').click();
            });

            var replyId = parseInt($(this).closest(testimonalsItemSelector).attr('data-id')),
                $form = $('.add-testimonials.main-comment-form').clone(false),
                formData = $('.main-comment-form ' + commentFormSelector).yiiActiveForm('data'),
                newFormId = 'comment-form-' + replyId,
                validationAttributes = [];

            $form.find('p.help-block.help-block-error').empty();
            $form.find('textarea').val('');
            $form.removeClass('main-comment-form');
            $form.addClass('reply');

            $form.find('.form-group').removeClass('has-success has-error');
            $form.find('.cancel-reply').removeClass('hidden');
            $form.find(commentFormSelector).attr('id', newFormId);
            $form.find(commentFormSelector).append($('<input>').attr({
                type: 'hidden',
                name: 'Comment[replyTo]',
                value: replyId
            }));

            for(var i in formData.attributes) {
                var attribute = formData.attributes[i];

                validationAttributes.push(attribute);
            }

            var $replyBlock = $(this).closest(commentWrapper).find(replyFormSelector);
            $replyBlock.empty();
            $replyBlock.append($form);
            $replyBlock.css('display', 'block');

            $(this).closest(commentWrapper).next(commentWrapper).find('.comment').removeClass('first-reply');

            $('#' + newFormId).yiiActiveForm(validationAttributes, {
                "validationUrl": pageOptions.validationUrl
            });
        })
        .on('click', '.reply-form .cancel-reply', function(e) {
            e.preventDefault();

            var $currentComment = $(this).closest(commentWrapper),
                currentDataDepth = $currentComment.find(testimonalsItemSelector).attr('data-depth'),
                nextDataDepth = $currentComment.next(commentWrapper).find(testimonalsItemSelector).attr('data-depth');

            if(currentDataDepth < nextDataDepth) {
                $currentComment.next(commentWrapper).find('.comment').addClass('first-reply');
            }

            $(this).closest(replyFormSelector).css('display', 'none');
            $(this).closest(replyFormSelector).html('');
        })
        .on('click', '.rating-btn', function(e) {
            e.preventDefault();
            if(!stateHolder.voteRequestFree) {
                return false;
            }

            if($(this).hasClass("disabled")) {
                new PNotify({
                    title: 'Ошибка',
                    text: 'Вы не можете голосовать дважды',
                    icon: '',
                    type: 'error',
                    delay: 8000 //Show the notification 4sec
                });

                return false;
            }

            var $btn = $(this);

            if($btn.hasClass('disabled')) {
                return false;
            }

            var commentId = $(this).closest(testimonalsItemSelector).attr('data-id'),
                rating = $(this).attr('data-rating'),
                $ratingText = $(this).closest('.rating').find('.total'),
                oldRatingText = $ratingText.text();

            $.ajax({
                url: pageOptions.voteUrl,
                method: 'POST',
                data: {commentId: commentId, rating: rating},
                dataType: 'json',
                beforeSend: function() {
                    stateHolder.voteRequestFree = false;
                    $ratingText.text('...')
                },
                success: function(response) {
                    if(typeof response.totalRating !== 'undefined') {
                        $ratingText.text(response.totalRating)
                    } else {
                        $ratingText.text(oldRatingText)
                    }

                    if(typeof response.blockBtn !== 'undefined') {
                        blockVoteBtn($btn, response.blockBtn)
                    }

                    stateHolder.voteRequestFree = true;
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    stateHolder.voteRequestFree = true;
                    $ratingText.text(oldRatingText)
                }
            });

            function blockVoteBtn($btn, blockBtn) {
                var $parent = $btn.closest('.rating');

                $parent.find('.rating-btn').removeClass('disabled');

                if(blockBtn !== 0) {
                    $parent.find('.rating-btn[data-rating=' + blockBtn + ']').addClass('disabled');
                }
            }
        });
};