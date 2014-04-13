<div class="custom"><a name="custom_anchor"></a>
    <div class="custom_lable">
        <input id="" name="theme_select" type="radio" value="Custom" {theme_select_custom} /> Custom
    </div>
    <table class="nbp_settings">
        <tr class="lables">
            <td></td><td colspan="2">Maximum length</td><td rowspan="14" style="width: 778px; padding: 0; line-height: 0;"><a href="http://newsboardplugin.com/support/faq/#how-to-go-pro" target="_blank">{custom_snapshot}</a></td>
        </tr>
        <tr class="theme_row">
            <td class="lable">Title:</td>
            <td colspan="2">
                <input id="title_cut_after" name="title_cut_after" type="text" class="number_input" value="{title_cut_after}"/> <input name="title_cutting_rule" type="radio" value="words" {title_cutting_rule_words}/> words &nbsp; <input name="title_cutting_rule" type="radio" value="symbols" {title_cutting_rule_symbols}/> symbols
            </td>
        </tr>
        <tr class="lables">
            <td></td><td>Show</td><td>Format</td>
        </tr>
        <tr class="theme_row">
            <td class="lable">Date:</td>
            <td class="check">
                <input name="show_date" type="checkbox" {show_date} value="1" />
            </td>
            <td>
                <input id="date_format_string" name="date_format_string" type="text" class="text_input" value="{date_format_string}"/>
            </td>
        </tr>
        <tr class="lables">
            <td></td><td>Show</td><td>Maximum length</td>
        </tr>
        <tr class="theme_row">
            <td class="lable">Text:</td>
            <td class="check">
                <input name="show_text" type="checkbox" {show_text} value="1" />
            </td>
            <td> 
                <input id="text_cut_after" name="text_cut_after" type="text" class="number_input" value="{text_cut_after}"/> <input name="text_cutting_rule" type="radio" {text_cutting_rule_words} value="words" /> words &nbsp; <input name="text_cutting_rule" type="radio" {text_cutting_rule_symbols} value="symbols" /> symbols
            </td>
        </tr>
        <tr class="lables">
            <td></td><td colspan="2">String</td>
        </tr>
        <tr class="theme_row">
            <td class="lable">"read more":</td>
            <td class="check" colspan="2">
                <input id="read_more_string" name="read_more_string" type="text" class="text_input" value="{read_more_string}"/>
            </td>
        </tr>
        <tr class="lables">
            <td></td><td colspan="2">Dimensions</td>
        </tr>
        <tr class="theme_row">
            <td class="lable">Background:</td>
            <td colspan="2">
                <input id="new_width" name="new_width" type="text" class="number_input" value="{new_width}" />px 
                &nbsp;&nbsp;x&nbsp;&nbsp;
                <input id="new_height" name="new_height" type="text" class="number_input" value="{new_height}"/>px
            </td>
        </tr>
        <tr class="lables">
            <td></td><td>Show</td><td>Dimensions</td>
        </tr>
        <tr class="theme_row">
            <td class="lable">Thumbnails:</td>
            <td class="check">
                <input name="show_thumbnails" type="checkbox" {show_thumbnails} value="1" />
            </td>
            <td> 
                <input id="thumbnail_width" name="thumbnail_width" type="text" class="number_input" value="{thumbnail_width}"/>px
                &nbsp;&nbsp;x&nbsp;&nbsp; 
                <input id="thumbnail_height" name="thumbnail_height" type="text" class="number_input" value="{thumbnail_height}"/>px
            </td>
        </tr>
        <tr class="lables">
            <td></td><td colspan="2">Height</td>
        </tr>
        <tr class="theme_row">
            <td class="lable">Bar:</td>
            <td colspan="2">
                <input id="bar_height" name="bar_height" type="text" class="number_input" value="{bar_height}"/>px
            </td>
        </tr>
    </table>
    <div class="nbp_luxury_btn" style="background-image: url({luxury_btn_bg});">
        <input type="checkbox" name="luxury_touch" {luxury_touch} value="1" />
        </div>
    <div class="nbp_submit_btn">
        <input name="nbp_app_settings_update" type="submit" class="button-primary" value="Save Changes" />
    </div>
</div>