<form method="post">
    <div class="nbp_settings_holder">
        <table class="nbp_settings">
            <tr class="main_row odd">
                <td class="lable">Number of news:</td>
                <td colspan="3"><input disabled="disabled" id="number_of_news" name="number_of_news" type="text" class="number_input" value="10"/>{go_pro_image}</td>
            </tr>
            <tr class="main_row">
                <td class="lable">Use categories:<br /><span class="notes">(hold down Ctrl to select multiple categories)</span></td>
                <td style="vertical-align: middle;"><input name="feed" type="radio" {feed_categories} value="categories" /></td>
                <td class="no_left_padding"><input id="select_all_categories" type="button" class="button-secondary" value="Select all" /><br /><select name="pickcategories[]" multiple="multiple" class="cat_select">{pickcategories_options}</select></td>
                <td class="cat_buttons">Order by: <select name="order_type">
                        {order_type_options}
                    </select><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="order" type="radio" {order_ASC} value="ASC" /> ASC <input name="order" type="radio" {order_DESC} value="DESC" /> DESC</td>
            </tr>
            <tr class="main_row odd">
                <td class="lable">Use RSS:</td>
                <td><input name="feed" type="radio" {feed_rss} value="rss" /></td><td colspan="2"><input id="rss_link" name="rss_link" type="text" class="longtext_input" value="{rss_link}" /></td>
            </tr>
            <tr class="main_row">
                <td class="lable">Open links in:</td>
                <td colspan="3"><input name="open_links_in" type="radio" {open_links_in_self} value="self" /> same tab &nbsp;&nbsp;&nbsp; <input name="open_links_in" type="radio" {open_links_in_blank} value="blank" /> new tab</td>
            </tr>
            <tr class="main_row odd">
                <td class="lable">Board fits:</td>
                <td colspan="3"><input id="board_news_fit" name="board_news_fit" type="text" class="number_input" value="{board_news_fit}" /> news.</td>
            </tr>
            <tr class="main_row">
                <td class="lable">Shown text is:</td>
                <td colspan="3"><input name="text_from" type="radio" {text_from_text} value="text" /> post's text &nbsp;&nbsp;&nbsp; <input name="text_from" type="radio" {text_from_excerpt} value="excerpt" /> post's excerpt</td>
            </tr>
            <tr class="main_row odd">
                <td class="lable">Auto scroll:</td>
                <td colspan="3"><input disabled="disabled" name="auto_scroll" type="checkbox" checked="checked" value="1" />{go_pro_image}</td>
            </tr> 
            <tr class="main_row">
                <td class="lable">Auto scroll behaviour:</td>
                <td colspan="3">
                    <input name="autoscroll_behaviour" type="radio" checked="checked" value="endless_down" /> endless down &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                    <input disabled="disabled" name="autoscroll_behaviour" type="radio" value="down_up_down" /> down - up - down &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                    <input disabled="disabled" name="autoscroll_behaviour" type="radio" value="endless_up" /> endless up &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{go_pro_image} 
                </td>
            </tr>
            <tr class="main_row odd">
                <td class="lable">Scroll every:</td>
                <td colspan="3"><input id="scroll_period" name="scroll_period" type="text" class="number_input" value="{scroll_period}"/>ms</td>
            </tr>
            <tr class="main_row">
                <td class="lable">Transition type:</td>
                <td colspan="3">
                    <select name="transition_type">
                        {transition_type_options}
                    </select>{go_pro_image}
                </td>
            </tr>
            <tr class="main_row odd">
                <td class="lable">Transition time:</td>
                <td colspan="3"><input id="transition_time" name="transition_time" type="text" class="number_input" value="{transition_time}"/>ms</td>
            </tr>
        </table>
    </div>
    <div class="nbp_submit_btn">
        <input name="nbp_main_settings_update" type="submit" class="button-primary" value="Save Changes" />
    </div>
</form>