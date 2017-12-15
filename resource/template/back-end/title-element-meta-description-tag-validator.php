// Count characters for seo elements
function titleElementLength(value){
    var maxLengthTitleElement = 65;
    var minLengthTitleElement = 40;

    if(value.length > maxLengthTitleElement || value.length < minLengthTitleElement) {
        return false;
    } else {
        return true;
    }
}

function metaDescriptionTagMaxLength(value){
    var maxLengthMetaDescriptionTag = 165;
    var minLengthMetaDescriptionTag = 130;

    if(value.length > maxLengthMetaDescriptionTag || value.length < minLengthMetaDescriptionTag) {
        return false;
    } else {
        return true;
    }
}

<?php $article_title_element_count < 40 || $article_title_element_count > 65 ? $current_alert = 'has-error' : $current_alert = 'has-success' ; ?>

document.getElementById('title_element').onkeyup = function(){
    if(!titleElementLength(this.value)) {
        $('#title_element_holder').removeClass("<?= $current_alert ?>");
        $('#title_element_holder').addClass("has-error");
    } else {
        $('#title_element_holder').addClass("has-success");
        $('#title_element_holder').removeClass("has-error");
    }

    // Character counter
    var title_element_length = $(this).val().length;
    $('#title_element_count_display').text(title_element_length);
}

<?php $article_meta_description_tag_count < 130 || $article_meta_description_tag_count > 165 ? $current_alert = 'has-error' : $current_alert = 'has-success' ; ?>

document.getElementById('meta_description_tag').onkeyup = function(){
    if(!metaDescriptionTagMaxLength(this.value)) {
        $('#meta_description_tag_holder').removeClass("<?= $has_alert ?>");
        $('#meta_description_tag_holder').addClass("has-error");
    } else {
        $('#meta_description_tag_holder').addClass("has-success");
        $('#meta_description_tag_holder').removeClass("has-error");
    }

    // Character counter
    var meta_description_tag_length = $(this).val().length;
    $('#meta_description_tag_display').text(meta_description_tag_length);
}
