jQuery(document).ready(function() {

    jQuery('#HgQuizAddAnswer').on('click', function(event) {
        event.preventDefault();
        let templateHtml =
            `<li class="hgquiz--metabox__answer">
                <label>Answer:
                    <input type="text" name="hgQuizAnswer[answer][]" value="" autocomplete="off" />
                </label>
                <label>Description:
                    <input type="text" name="hgQuizAnswer[answerDesc][]" value="" autocomplete="off" />
                </label>
                <label>Is main answer?
                    <input type="checkbox" name="hgQuizAnswer[isFinal][]" value="isFinal" />
                    <input type="hidden" name="hgQuizAnswer[isFinal][]" value="placeholder" />
                </label>
                <button onclick="event.preventDefault(); removeAnswer(this);" class="hgquiz--metabox__button button--remove">Remove</button>
            </li>`;

        jQuery('#HgQuizAnswersBox').append(templateHtml);
    });

});

function removeAnswer(elem)
{
    console.log('hi');
    jQuery(elem).parent().remove();
}