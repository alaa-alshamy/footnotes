<?php // phpcs:disable WordPress.Files.FileName.InvalidClassFileName
/**
 * Includes the Settings class to handle all Plugin settings.
 *
 * @filesource
 * @package footnotes
 * @since 1.5.0
 * @date 14.09.14 10:43
 *
 * @since 2.0.0  Update: **symbol for backlinks** removed; hyperlink moved to the reference number.
 * @since 2.0.4  Update: Restore arrow settings to customize or disable the now prepended arrow symbol, thanks to @mmallett issue report.
 * @since 2.0.7  BUGFIX: Hooks: Default-disable 'the_post', thanks to @spaceling @markcheret @nyamachi @whichgodsaves @spiralofhope2 @mmallett @andreasra @widecast @ymorin007 @tashi1es bug reports.
 * @since 2.1.3  Bugfix: Hooks: disable the_excerpt hook by default to fix issues, thanks to @nikelaos bug report.
 *
 * **Revision of the docblocks is in progress.**
 *
 * @since 2.1.3  fix ref container positioning by priority level  2020-11-17T0205+0100
 *
 * @since 2.1.4  more settings container keys  2020-12-03T0955+0100
 *
 * @since 2.1.6  option to disable URL line wrapping   2020-12-09T1606+0100
 *
 * @since 2.1.6  set default priority level of the_content to 98 to prevent plugin conflict, thanks to @marthalindeman   2020-12-10T0447+0100
 *
 * @since 2.2.0  reference container custom position shortcode, thanks to @hamshe   2020-12-13T2056+0100
 * @link https://wordpress.org/support/topic/reference-container-in-elementor/
 *
 * @since 2.2.2  Custom CSS settings container migration  2020-12-15T0709+0100
 *
 * @since 2.2.4  move backlink symbol selection under previous tab  2020-12-16T1256+0100
 *
 * @since 2.2.5  alternative tooltip position settings  2020-12-17T0907+0100
 *
 * @since 2.2.5  options for reference container label element and bottom border, thanks to @markhillyer    2020-12-18T1455+0100
 * @link https://wordpress.org/support/topic/how-do-i-eliminate-the-horizontal-line-beneath-the-reference-container-heading/
 *
 * @since 2.2.9  set default priority level of widget_text to 98 like for the_content (since 2.1.6), thanks to @marthalindeman   2020-12-25T1646+0100
 *
 * @since 2.2.10 reference container row border option, thanks to @noobishh   2020-12-25T2316+0100
 * @link https://wordpress.org/support/topic/borders-25/
 *
 * @since 2.3.0  reference container: settings for top (and bottom) margin, thanks to @hamshe
 * @link https://wordpress.org/support/topic/reference-container-in-elementor/#post-13786635
 *
 * @since 2.3.0  Bugfix: Dashboard: Custom CSS: swap migration Boolean, meaning 'show legacy' instead of 'migration complete', due to storage data structure constraints.
 * @date 2020-12-27T1243+0100

 * @since 2.3.0  referrers, reference container: settings for anchor slugs  2020-12-31T1429+0100
 *
 * @since 2.4.0  footnote shortcode syntax validation  2021-01-01T0624+0100
 */

/**
 * Loads the settings values, sets to default values if undefined.
 *
 * @since 1.5.0
 */
class MCI_Footnotes_Settings {

	/**
	 * Settings container key for the label of the reference container.
	 *
	 * @since 1.5.0
	 * @var str
	 */
	const C_STR_REFERENCE_CONTAINER_NAME = 'footnote_inputfield_references_label';

	/**
	 * Settings container key to collapse the reference container by default.
	 *
	 * @since 1.5.0
	 * @var str
	 * The string is converted to Boolean false if 'no', true if 'yes'.
	 * @see MCI_Footnotes_Convert::to_bool()
	 */
	const C_STR_REFERENCE_CONTAINER_COLLAPSE = 'footnote_inputfield_collapse_references';

	/**
	 * Settings container key for the position of the reference container.
	 *
	 * @since 1.5.0
	 * @var str
	 */
	const C_STR_REFERENCE_CONTAINER_POSITION = 'footnote_inputfield_reference_container_place';

	/**
	 * Settings container key for combining identical footnotes.
	 *
	 * @since 1.5.0
	 * @var str
	 */
	const C_STR_COMBINE_IDENTICAL_FOOTNOTES = 'footnote_inputfield_combine_identical';

	/**
	 * Settings container key for the short code of the footnote’s start.
	 *
	 * @since 1.5.0
	 * @var str
	 */
	const C_STR_FOOTNOTES_SHORT_CODE_START = 'footnote_inputfield_placeholder_start';

	/**
	 * Settings container key for the short code of the footnote’s end.
	 *
	 * @since 1.5.0
	 * @var str
	 */
	const C_STR_FOOTNOTES_SHORT_CODE_END = 'footnote_inputfield_placeholder_end';

	/**
	 * Settings container key for the user-defined short code of the footnotes start.
	 *
	 * @since 1.5.0
	 * @var str
	 */
	const C_STR_FOOTNOTES_SHORT_CODE_START_USER_DEFINED = 'footnote_inputfield_placeholder_start_user_defined';

	/**
	 * Settings container key for the user-defined short code of the footnotes end.
	 *
	 * @since 1.5.0
	 * @var str
	 */
	const C_STR_FOOTNOTES_SHORT_CODE_END_USER_DEFINED = 'footnote_inputfield_placeholder_end_user_defined';

	/**
	 * Settings container key for the counter style of the footnotes.
	 *
	 * @since 1.5.0
	 * @var str
	 */
	const C_STR_FOOTNOTES_COUNTER_STYLE = 'footnote_inputfield_counter_style';

	/**
	 * Settings container key for the backlink symbol selection.
	 *
	 * @since 1.5.0
	 * @var str
	 *
	 * - Update: Restore arrow settings to customize or disable the now prepended arrow symbol, thanks to @mmallett issue report.
	 *
	 * @since 2.0.4
	 * @date 2020-11-02T2115+0100
	 *
	 * @reporter @mmallett
	 * @link https://wordpress.org/support/topic/mouse-over-broken/#post-13593037
	 */
	const C_STR_HYPERLINK_ARROW = 'footnote_inputfield_custom_hyperlink_symbol';

	/**
	 * Settings container key for the user-defined backlink symbol.
	 *
	 * @since 1.5.0
	 * @var str
	 */
	const C_STR_HYPERLINK_ARROW_USER_DEFINED = 'footnote_inputfield_custom_hyperlink_symbol_user';

	/**
	 * Settings container key to look for footnotes in post excerpts.
	 *
	 * @since 1.5.0
	 * @var str
	 *
	 * - Bugfix: Hooks: disable the_excerpt hook by default to fix issues, thanks to @nikelaos bug report.
	 *
	 * @reporter @nikelaos
	 * @link https://wordpress.org/support/topic/doesnt-work-any-more-11/#post-13687068
	 */
	const C_STR_FOOTNOTES_IN_EXCERPT = 'footnote_inputfield_search_in_excerpt';

	/**
	 * Settings container key for the string before the footnote referrer.
	 *
	 * @since 1.5.0
	 * @var str
	 *
	 * The default footnote referrer surroundings should be square brackets.
	 *
	 * - with respect to baseline footnote referrers new option;
	 * - as in English or US American typesetting;
	 * - for better UX thanks to a more button-like appearance;
	 * - for stylistic consistency with the expand-collapse button.
	 */
	const C_STR_FOOTNOTES_STYLING_BEFORE = 'footnote_inputfield_custom_styling_before';

	/**
	 * Settings container key for the string after the footnote referrer.
	 *
	 * @since 1.5.0
	 * @var str
	 */
	const C_STR_FOOTNOTES_STYLING_AFTER = 'footnote_inputfield_custom_styling_after';

	/**
	 * Settings container key for the Custom CSS.
	 *
	 * @since 1.5.0
	 * @var str
	 *
	 * @since 1.3.0  Adding: new settings tab for custom CSS settings.
	 * Custom CSS migrates to a dedicated tab.
	 */
	const C_STR_CUSTOM_CSS = 'footnote_inputfield_custom_css';

	/**
	 * Settings container key for the 'I love footnotes' text.
	 *
	 * @since 1.5.0
	 * @var str
	 */
	const C_STR_FOOTNOTES_LOVE = 'footnote_inputfield_love';

	/**
	 * Settings container key to enable the mouse-over box.
	 *
	 * @since 1.5.2
	 * @var str
	 */
	const C_STR_FOOTNOTES_MOUSE_OVER_BOX_ENABLED = 'footnote_inputfield_custom_mouse_over_box_enabled';

	/**
	 * Settings container key to enable tooltip truncation.
	 *
	 * @since 1.5.4
	 * @var str
	 * The mouse over content truncation should be enabled by default.
	 * To raise awareness of the functionality and to prevent the screen.
	 * From being filled at mouse-over, and to allow the Continue reading.
	 */
	const C_STR_FOOTNOTES_MOUSE_OVER_BOX_EXCERPT_ENABLED = 'footnote_inputfield_custom_mouse_over_box_excerpt_enabled';

	/**
	 * Settings container key for the mouse-over box to define the max. length of the enabled excerpt.
	 *
	 * @since 1.5.4
	 * @var str
	 * The truncation length is raised from 150 to 200 chars.
	 */
	const C_INT_FOOTNOTES_MOUSE_OVER_BOX_EXCERPT_LENGTH = 'footnote_inputfield_custom_mouse_over_box_excerpt_length';

	/**
	 * Settings container key to enable the 'the_title' hook.
	 *
	 * @since 1.5.5
	 * @var str
	 * These are checkboxes; keyword 'checked' is converted to Boolean true,.
	 * Empty string to false (default).
	 * Titles should all be enabled by default to prevent users from.
	 * Thinking at first that the feature is broken in post titles..
	 * See <https://wordpress.org/support/topic/more-feature-ideas/>.
	 * Yet in titles, footnotes are still buggy, because WordPress.
	 * Uses the title string in menus and in the title element..
	 */
	const C_STR_EXPERT_LOOKUP_THE_TITLE = 'footnote_inputfield_expert_lookup_the_title';

	/**
	 * Settings container key to enable the 'the_content' hook.
	 *
	 * @since 1.5.5
	 * @var str
	 */
	const C_STR_EXPERT_LOOKUP_THE_CONTENT = 'footnote_inputfield_expert_lookup_the_content';

	/**
	 * Settings container key to enable the 'the_excerpt' hook.
	 *
	 * @since 1.5.5
	 * @var str
	 *
	 * @since 2.1.3  excerpt hook: disable by default, thanks to @nikelaos
	 * @link https://wordpress.org/support/topic/doesnt-work-any-more-11/#post-13687068
	 * And the_excerpt is disabled by default following @nikelaos in.
	 * <https://wordpress.org/support/topic/jquery-comes-up-in-feed-content/#post-13110879>.
	 * <https://wordpress.org/support/topic/doesnt-work-any-more-11/#post-13687068>.
	 */
	const C_STR_EXPERT_LOOKUP_THE_EXCERPT = 'footnote_inputfield_expert_lookup_the_excerpt';

	/**
	 * Settings container key to enable the 'widget_title' hook.
	 *
	 * @since 1.5.5
	 * @var str
	 */
	const C_STR_EXPERT_LOOKUP_WIDGET_TITLE = 'footnote_inputfield_expert_lookup_widget_title';

	/**
	 * Settings container key to enable the 'widget_text' hook.
	 *
	 * @since 1.5.5
	 * @var str
	 * The widget_text hook must be disabled by default, because it causes.
	 * Multiple reference containers to appear in Elementor accordions, but.
	 * It must be enabled if multiple reference containers are desired, as.
	 * In Elementor toggles..
	 */
	const C_STR_EXPERT_LOOKUP_WIDGET_TEXT = 'footnote_inputfield_expert_lookup_widget_text';

	/**
	 * Settings container key for the Expert mode.
	 *
	 * @since 1.5.5
	 * @var str
	 *
	 * @since 2.1.6  This setting removed as irrelevant since priority level settings need permanent visibility.
	 * @date 2020-12-09T2107+0100
	 *
	 * Since the removal of the the_post hook, the tab is no danger zone any longer.
	 * All users, not experts only, need to be able to control relative positioning.
	 * @date 2020-11-06T1342+0100
	 */
	const C_STR_FOOTNOTES_EXPERT_MODE = 'footnote_inputfield_enable_expert_mode';

	/**
	 * Settings container key for the mouse-over box to define the color.
	 *
	 * @since 1.5.6
	 * @var str
	 */
	const C_STR_FOOTNOTES_MOUSE_OVER_BOX_COLOR = 'footnote_inputfield_custom_mouse_over_box_color';

	/**
	 * Settings container key for the mouse-over box to define the background color.
	 *
	 * @since 1.5.6
	 * @var str
	 *
	 * - Bugfix: Tooltips: Styling: Background color: empty default value to adopt theme background, thanks to 4msc bug report.
	 *
	 * @since 2.5.11
	 *
	 * @reporter @4msc
	 * @link https://wordpress.org/support/topic/tooltip-not-showing-on-dark-theme-with-white-text/
	 *
	 * 1.2.5..1.5.5  #fff7a7 hard-coded
	 * 1.5.6..2.0.6  #fff7a7 setting default
	 * 2.0.7..2.5.10 #ffffff setting default
	 * The mouse over box shouldn’t feature a colored background.
	 * By default, due to diverging user preferences. White is neutral.
	 * Theme default background color is best.
	 */
	const C_STR_FOOTNOTES_MOUSE_OVER_BOX_BACKGROUND = 'footnote_inputfield_custom_mouse_over_box_background';

	/**
	 * Settings container key for the mouse-over box to define the border width.
	 *
	 * @since 1.5.6
	 * @var str
	 */
	const C_INT_FOOTNOTES_MOUSE_OVER_BOX_BORDER_WIDTH = 'footnote_inputfield_custom_mouse_over_box_border_width';

	/**
	 * Settings container key for the mouse-over box to define the border color.
	 *
	 * @since 1.5.6
	 * @var str
	 */
	const C_STR_FOOTNOTES_MOUSE_OVER_BOX_BORDER_COLOR = 'footnote_inputfield_custom_mouse_over_box_border_color';

	/**
	 * Settings container key for the mouse-over box to define the border radius.
	 *
	 * @since 1.5.6
	 * @var str
	 * The mouse over box corners mustn’t be rounded as that is outdated.
	 */
	const C_INT_FOOTNOTES_MOUSE_OVER_BOX_BORDER_RADIUS = 'footnote_inputfield_custom_mouse_over_box_border_radius';

	/**
	 * Settings container key for the mouse-over box to define the max. width.
	 *
	 * @since 1.5.6
	 * @var str
	 * The width should be limited to start with, for the box to have shape.
	 */
	const C_INT_FOOTNOTES_MOUSE_OVER_BOX_MAX_WIDTH = 'footnote_inputfield_custom_mouse_over_box_max_width';

	/**
	 * Settings container key for the mouse-over box to define the position.
	 *
	 * @since 1.5.7
	 * @var str
	 * The default position should not be lateral because of the risk.
	 * The box gets squeezed between note anchor at line end and window edge,.
	 * And top because reading at the bottom of the window is more likely.
	 */
	const C_STR_FOOTNOTES_MOUSE_OVER_BOX_POSITION = 'footnote_inputfield_custom_mouse_over_box_position';

	/**
	 * Settings container key for the mouse-over box to define the offset (x).
	 *
	 * @since 1.5.7
	 * @var str
	 */
	const C_INT_FOOTNOTES_MOUSE_OVER_BOX_OFFSET_X = 'footnote_inputfield_custom_mouse_over_box_offset_x';

	/**
	 * Settings container key for the mouse-over box to define the offset (y).
	 *
	 * @since 1.5.7
	 * @var str
	 * The vertical offset must be negative for the box not to cover.
	 * The current line of text (web coordinates origin is top left).
	 */
	const C_INT_FOOTNOTES_MOUSE_OVER_BOX_OFFSET_Y = 'footnote_inputfield_custom_mouse_over_box_offset_y';

	/**
	 * Settings container key for the mouse-over box to define the box-shadow color.
	 *
	 * @since 1.5.8
	 * @var str
	 */
	const C_STR_FOOTNOTES_MOUSE_OVER_BOX_SHADOW_COLOR = 'footnote_inputfield_custom_mouse_over_box_shadow_color';

	/**
	 * Settings container key for the label of the Read-on button in truncated tooltips.
	 *
	 * - Adding: Tooltips: Read-on button: Label: configurable instead of localizable, thanks to @rovanov example provision.
	 *
	 * @since 2.1.0
	 * @date 2020-11-08T2106+0100
	 *
	 * @reporter @rovanov
	 * @link https://wordpress.org/support/topic/offset-x-axis-and-offset-y-axis-does-not-working/
	 *
	 * @var str
	 */
	const C_STR_FOOTNOTES_TOOLTIP_READON_LABEL = 'footnote_inputfield_readon_label';

	/**
	 * Settings container key to enable the alternative tooltips.
	 *
	 * - Bugfix: Tooltips: optional alternative JS implementation with CSS transitions to fix configuration-related outage, thanks to @andreasra feedback.
	 *
	 * @since 2.1.1
	 * @date 2020-11-11T1817+0100
	 *
	 * @reporter @andreasra
	 * @link https://wordpress.org/support/topic/footnotes-appearing-in-header/page/2/#post-13632566
	 *
	 * @var str
	 *
	 * These alternative tooltips work around a website related jQuery UI
	 * outage. They are low-script but use the AMP incompatible onmouseover
	 * and onmouseout arguments, along with CSS transitions for fade-in/out.
	 * The very small script is inserted after Footnotes’ internal stylesheet.
	 */
	const C_STR_FOOTNOTES_MOUSE_OVER_BOX_ALTERNATIVE = 'footnote_inputfield_custom_mouse_over_box_alternative';

	/**
	 * Settings container key for the referrer element.
	 *
	 * - Bugfix: Referrers: new setting for vertical align: superscript (default) or baseline (optional), thanks to @cwbayer bug report.
	 *
	 * @since 2.1.1
	 * @date 2020-11-16T0859+0100
	 *
	 * @reporter @cwbayer
	 * @link https://wordpress.org/support/topic/footnote-number-in-text-superscript-disrupts-leading/
	 *
	 * @var str
	 */
	const C_STR_FOOTNOTES_REFERRER_SUPERSCRIPT_TAGS = 'footnotes_inputfield_referrer_superscript_tags';

	/**
	 * Settings container key to enable the display of a backlink symbol.
	 *
	 * - Bugfix: Reference container: Backlink symbol: make optional, not suggest configuring it to invisible, thanks to @spaceling feedback.
	 *
	 * @since 2.1.1
	 * @date 2020-11-16T2021+0100
	 *
	 * @reporter @spaceling
	 * @link https://wordpress.org/support/topic/change-the-position-5/page/2/#post-13671138
	 *
	 * @var str
	 */
	const C_STR_REFERENCE_CONTAINER_BACKLINK_SYMBOL_ENABLE = 'footnotes_inputfield_reference_container_backlink_symbol_enable';

	/**
	 * Settings container key to not display the reference container on the homepage.
	 *
	 * - Bugfix: Reference container: fix start pages by making its display optional, thanks to @dragon013 bug report.
	 *
	 * @since 2.1.1
	 *
	 * @reporter @dragon013
	 * @link https://wordpress.org/support/topic/possible-to-hide-it-from-start-page/
	 *
	 * @var str
	 */
	const C_STR_REFERENCE_CONTAINER_START_PAGE_ENABLE = 'footnotes_inputfield_reference_container_start_page_enable';

	/**
	 * Settings container key to enable the legacy layout of the reference container.
	 *
	 * - Bugfix: Reference container: option to restore pre-2.0.0 layout with the backlink symbol in an extra column.
	 *
	 * @since 2.1.1
	 * @var str
	 */
	const C_STR_REFERENCE_CONTAINER_3COLUMN_LAYOUT_ENABLE = 'footnotes_inputfield_reference_container_3column_layout_enable';

	/**
	 * Settings container key to get the backlink symbol switch side.
	 *
	 * - Bugfix: Reference container: option to append symbol (prepended by default), thanks to @spaceling code contribution.
	 *
	 * @since 2.1.1
	 * @date 2020-11-16T2024+0100
	 *
	 * @contributor @spaceling
	 * @link https://wordpress.org/support/topic/change-the-position-5/#post-13615994
	 *
	 * @var str
	 */
	const C_STR_REFERENCE_CONTAINER_BACKLINK_SYMBOL_SWITCH = 'footnotes_inputfield_reference_container_backlink_symbol_switch';

	/**
	 * Settings container key for 'the_content' hook priority level.
	 *
	 * @since 2.1.1
	 * @date 2020-11-16T0859+0100
	 *
	 * @var str
	 * Priority level of the_content and of widget_text as the only relevant.
	 * Hooks must be less than 99 because social buttons may yield scripts.
	 * That contain the strings '((' and '))', i.e. the default footnote.
	 * Start and end short codes, causing issues with fake footnotes..
	 */
	const C_INT_EXPERT_LOOKUP_THE_CONTENT_PRIORITY_LEVEL = 'footnote_inputfield_expert_lookup_the_content_priority_level';

	/**
	 * Settings container key for 'the_title' hook priority level.
	 *
	 * @since 2.1.2
	 * @date 2020-11-20T0620+0100
	 *
	 * @var str
	 * Initially hard-coded default.
	 * Shows "9223372036854780000" instead of 9223372036854775807 in the numbox.
	 * Empty should be interpreted as PHP_INT_MAX, but a numbox cannot be set to empty.
	 * <https://github.com/Modernizr/Modernizr/issues/171>.
	 * Interpret -1 as PHP_INT_MAX instead.
	 */
	const C_INT_EXPERT_LOOKUP_THE_TITLE_PRIORITY_LEVEL    = 'footnote_inputfield_expert_lookup_the_title_priority_level';

	/**
	 * Settings container key for 'widget_title' hook priority level.
	 *
	 * @since 2.1.2
	 * @date 2020-11-20T0620+0100
	 *
	 * @var str
	 */
	const C_INT_EXPERT_LOOKUP_WIDGET_TITLE_PRIORITY_LEVEL = 'footnote_inputfield_expert_lookup_widget_title_priority_level';

	/**
	 * Settings container key for 'widget_text' hook priority level.
	 *
	 * @since 2.1.2
	 * @date 2020-11-20T0620+0100
	 *
	 * @var str
	 */
	const C_INT_EXPERT_LOOKUP_WIDGET_TEXT_PRIORITY_LEVEL  = 'footnote_inputfield_expert_lookup_widget_text_priority_level';

	/**
	 * Settings container key for 'the_excerpt' hook priority level.
	 *
	 * @since 2.1.2
	 * @date 2020-11-20T0620+0100
	 *
	 * @var str
	 */
	const C_INT_EXPERT_LOOKUP_THE_EXCERPT_PRIORITY_LEVEL  = 'footnote_inputfield_expert_lookup_the_excerpt_priority_level';

	/**
	 * Settings container key for the link element option.
	 *
	 * @since 2.1.4
	 * @var str
	 * Whether to use link elements.
	 * Link element option.
	 */
	const C_STR_LINK_ELEMENT_ENABLED = 'footnote_inputfield_link_element_enabled';

	/**
	 * Settings container key to enable the presence of a backlink separator.
	 *
	 * @since 2.1.4
	 * @var str
	 * Backlink typography.
	 * Backlink separators and terminators are often not preferred..
	 * But a choice must be provided along with the ability to customize.
	 */
	const C_STR_BACKLINKS_SEPARATOR_ENABLED  = 'footnotes_inputfield_backlinks_separator_enabled';

	/**
	 * Settings container key for the backlink separator options.
	 *
	 * @since 2.1.4
	 * @var str
	 */
	const C_STR_BACKLINKS_SEPARATOR_OPTION   = 'footnotes_inputfield_backlinks_separator_option';

	/**
	 * Settings container key for a custom backlink separator.
	 *
	 * @since 2.1.4
	 * @var str
	 */
	const C_STR_BACKLINKS_SEPARATOR_CUSTOM   = 'footnotes_inputfield_backlinks_separator_custom';

	/**
	 * Settings container key to enable the presence of a backlink terminator.
	 *
	 * @since 2.1.4
	 * @var str
	 */
	const C_STR_BACKLINKS_TERMINATOR_ENABLED = 'footnotes_inputfield_backlinks_terminator_enabled';

	/**
	 * Settings container key for the backlink terminator options.
	 *
	 * @since 2.1.4
	 * @var str
	 */
	const C_STR_BACKLINKS_TERMINATOR_OPTION  = 'footnotes_inputfield_backlinks_terminator_option';

	/**
	 * Settings container key for a custom backlink terminator.
	 *
	 * @since 2.1.4
	 * @var str
	 */
	const C_STR_BACKLINKS_TERMINATOR_CUSTOM  = 'footnotes_inputfield_backlinks_terminator_custom';

	/**
	 * Settings container key to enable the backlinks column width.
	 *
	 * @since 2.1.4
	 * @var str
	 * Set backlinks column width.
	 * Backlink layout.
	 */
	const C_STR_BACKLINKS_COLUMN_WIDTH_ENABLED     = 'footnotes_inputfield_backlinks_column_width_enabled';

	/**
	 * Settings container key for the backlinks column width scalar.
	 *
	 * @since 2.1.4
	 * @var int
	 */
	const C_INT_BACKLINKS_COLUMN_WIDTH_SCALAR      = 'footnotes_inputfield_backlinks_column_width_scalar';

	/**
	 * Settings container key for the backlinks column width unit.
	 *
	 * @since 2.1.4
	 * @var str
	 */
	const C_STR_BACKLINKS_COLUMN_WIDTH_UNIT        = 'footnotes_inputfield_backlinks_column_width_unit';

	/**
	 * Settings container key to enable a max width for the backlinks column.
	 *
	 * @since 2.1.4
	 * @var str
	 */
	const C_STR_BACKLINKS_COLUMN_MAX_WIDTH_ENABLED = 'footnotes_inputfield_backlinks_column_max_width_enabled';

	/**
	 * Settings container key for the backlinks column max width scalar.
	 *
	 * @since 2.1.4
	 * @var int
	 */
	const C_INT_BACKLINKS_COLUMN_MAX_WIDTH_SCALAR  = 'footnotes_inputfield_backlinks_column_max_width_scalar';

	/**
	 * Settings container key for the backlinks column max width unit.
	 *
	 * @since 2.1.4
	 * @var str
	 */
	const C_STR_BACKLINKS_COLUMN_MAX_WIDTH_UNIT    = 'footnotes_inputfield_backlinks_column_max_width_unit';

	/**
	 * Settings container key to enable line breaks between backlinks.
	 *
	 * @since 2.1.4
	 * @var str
	 * Whether a <br /> tag is inserted.
	 */
	const C_STR_BACKLINKS_LINE_BREAKS_ENABLED      = 'footnotes_inputfield_backlinks_line_breaks_enabled';

	/**
	 * Settings container key to enable setting the tooltip font size.
	 *
	 * @since 2.1.4
	 * @var str
	 * Tooltip font size.
	 * Called mouse over box not tooltip for consistency.
	 * Tooltip font size reset to legacy by default since 2.1.4;.
	 * Was set to inherit since 2.1.1 as it overrode custom CSS,.
	 * Is moved to settings since 2.1.4    2020-12-04T1023+0100.
	 */
	const C_STR_MOUSE_OVER_BOX_FONT_SIZE_ENABLED = 'footnotes_inputfield_mouse_over_box_font_size_enabled';

	/**
	 * Settings container key for the scalar value of the tooltip font size.
	 *
	 * @since 2.1.4
	 * @var flo
	 */
	const C_FLO_MOUSE_OVER_BOX_FONT_SIZE_SCALAR  = 'footnotes_inputfield_mouse_over_box_font_size_scalar';

	/**
	 * Settings container key for the unit of the tooltip font size.
	 *
	 * @since 2.1.4
	 * @var str
	 */
	const C_STR_MOUSE_OVER_BOX_FONT_SIZE_UNIT    = 'footnotes_inputfield_mouse_over_box_font_size_unit';

	/**
	 * Settings container key for basic responsive page layout support options.
	 *
	 * @since 2.1.4
	 * @var str
	 * Whether to enqueue additional stylesheet.
	 * Page layout support.
	 */
	const C_STR_FOOTNOTES_PAGE_LAYOUT_SUPPORT = 'footnotes_inputfield_page_layout_support';

	/**
	 * Settings container key for scroll offset.
	 *
	 * - Bugfix: Scroll offset: make configurable to fix site-dependent issues related to fixed headers.
	 *
	 * @since 2.1.4
	 * @date 2020-12-05T0538+0100
	 * @var int
	 */
	const C_INT_FOOTNOTES_SCROLL_OFFSET   = 'footnotes_inputfield_scroll_offset';

	/**
	 * Settings container key for scroll duration.
	 *
	 * - Bugfix: Scroll duration: make configurable to conform to website content and style requirements.
	 *
	 * @since 2.1.4
	 * @date 2020-12-05T0538+0100
	 * @var int
	 */
	const C_INT_FOOTNOTES_SCROLL_DURATION = 'footnotes_inputfield_scroll_duration';

	/**
	 * Settings container key for tooltip display fade-in delay.
	 *
	 * @since 2.1.4
	 * @date 2020-12-05T0538+0100
	 * @var int
	 * Called mouse over box not tooltip for consistency.
	 */
	const C_INT_MOUSE_OVER_BOX_FADE_IN_DELAY     = 'footnotes_inputfield_mouse_over_box_fade_in_delay';

	/**
	 * Settings container key for tooltip display fade-in duration.
	 *
	 * @since 2.1.4
	 * @date 2020-12-05T0538+0100
	 * @var int
	 */
	const C_INT_MOUSE_OVER_BOX_FADE_IN_DURATION  = 'footnotes_inputfield_mouse_over_box_fade_in_duration';

	/**
	 * Settings container key for tooltip display fade-out delay.
	 *
	 * @since 2.1.4
	 * @date 2020-12-05T0538+0100
	 * @var int
	 */
	const C_INT_MOUSE_OVER_BOX_FADE_OUT_DELAY    = 'footnotes_inputfield_mouse_over_box_fade_out_delay';

	/**
	 * Settings container key for tooltip display fade-out duration.
	 *
	 * @since 2.1.4
	 * @date 2020-12-05T0538+0100
	 * @var int
	 */
	const C_INT_MOUSE_OVER_BOX_FADE_OUT_DURATION = 'footnotes_inputfield_mouse_over_box_fade_out_duration';

	/**
	 * Settings container key for URL wrap option.
	 *
	 * This is made optional because it causes weird line breaks.
	 * Unicode-compliant browsers break URLs at slashes.
	 *
	 * @since 2.1.6
	 * @date 2020-12-09T1554+0100..2020-12-13T1313+0100
	 * @var str
	 */
	const C_STR_FOOTNOTE_URL_WRAP_ENABLED = 'footnote_inputfield_url_wrap_enabled';

	/**
	 * Settings container key for reference container position shortcode.
	 *
	 * @since 2.2.0
	 * @date 2020-12-13T2056+0100
	 * @var str
	 */
	const C_STR_REFERENCE_CONTAINER_POSITION_SHORTCODE = 'footnote_inputfield_reference_container_position_shortcode';

	/**
	 * Settings container key for the Custom CSS migrated to a dedicated tab.
	 *
	 * @since 2.2.2  Bugfix: Dashboard: Custom CSS: unearth text area and migrate to dedicated tab as designed.
	 * @date 2020-12-15T0520+0100
	 * @var str
	 */
	const C_STR_CUSTOM_CSS_NEW = 'footnote_inputfield_custom_css_new';

	/**
	 * Settings container key to enable display of legacy Custom CSS metaboxes.
	 *
	 * @since 2.2.2
	 * @date 2020-12-15T0520+0100
	 * @var str
	 *
	 * @since 2.3.0  swap Boolean from 'migration complete' to 'show legacy'
	 * @date 2020-12-27T1233+0100
	 *
	 * The Boolean must be false if its setting is contained in the container to be hidden,
	 * because when saving, all missing constants are emptied, and to_bool() converts empty to false.
	 */
	const C_STR_CUSTOM_CSS_LEGACY_ENABLE = 'footnote_inputfield_custom_css_legacy_enable';

	/**
	 * Settings container key for alternative tooltip position.
	 *
	 * @since 2.2.5
	 * @date 2020-12-17T0746+0100
	 * @var str
	 *
	 * Fixed width is for alternative tooltips, cannot reuse max-width nor offsets.
	 */
	const C_STR_FOOTNOTES_ALTERNATIVE_MOUSE_OVER_BOX_POSITION = 'footnotes_inputfield_alternative_mouse_over_box_position';

	/**
	 * Settings container key for alternative tooltip x offset.
	 *
	 * @since 2.2.5
	 * @date 2020-12-17T0746+0100
	 * @var int
	 */
	const C_INT_FOOTNOTES_ALTERNATIVE_MOUSE_OVER_BOX_OFFSET_X = 'footnotes_inputfield_alternative_mouse_over_box_offset_x';

	/**
	 * Settings container key for alternative tooltip y offset.
	 *
	 * @since 2.2.5
	 * @date 2020-12-17T0746+0100
	 * @var int
	 */
	const C_INT_FOOTNOTES_ALTERNATIVE_MOUSE_OVER_BOX_OFFSET_Y = 'footnotes_inputfield_alternative_mouse_over_box_offset_y';

	/**
	 * Settings container key for alternative tooltip width.
	 *
	 * @since 2.2.5
	 * @date 2020-12-17T0746+0100
	 * @var int
	 */
	const C_INT_FOOTNOTES_ALTERNATIVE_MOUSE_OVER_BOX_WIDTH    = 'footnotes_inputfield_alternative_mouse_over_box_width';


	/**
	 * Settings container key for the reference container label element.
	 *
	 * - Bugfix: Reference container: Label: option to select paragraph or heading element, thanks to @markhillyer issue report.
	 *
	 * @reporter @markhillyer
	 * @link https://wordpress.org/support/topic/how-do-i-eliminate-the-horizontal-line-beneath-the-reference-container-heading/
	 *
	 * @since 2.2.5
	 * @date 2020-12-18T1509+0100
	 * @var str
	 */
	const C_STR_REFERENCE_CONTAINER_LABEL_ELEMENT       = 'footnotes_inputfield_reference_container_label_element';

	/**
	 * Settings container key to enable the reference container label bottom border.
	 *
	 * - Bugfix: Reference container: Label: make bottom border an option, thanks to @markhillyer issue report.
	 *
	 * @reporter @markhillyer
	 * @link https://wordpress.org/support/topic/how-do-i-eliminate-the-horizontal-line-beneath-the-reference-container-heading/
	 *
	 * @since 2.2.5
	 * @date 2020-12-18T1509+0100
	 * @var str
	 */
	const C_STR_REFERENCE_CONTAINER_LABEL_BOTTOM_BORDER = 'footnotes_inputfield_reference_container_label_bottom_border';

	/**
	 * Settings container key to enable reference container table row borders.
	 *
	 * - Bugfix: Reference container: add option for table borders to restore pre-2.0.0 design, thanks to @noobishh issue report.
	 *
	 * @reporter @noobishh
	 * @link https://wordpress.org/support/topic/borders-25/
	 *
	 * @since 2.2.10
	 * @date 2020-12-25T2311+0100
	 * @var str
	 */
	const C_STR_REFERENCE_CONTAINER_ROW_BORDERS_ENABLE = 'footnotes_inputfield_reference_container_row_borders_enable';

	/**
	 * Settings container key for reference container top margin.
	 *
	 * - Bugfix: Reference container: convert top padding to margin and make it a setting, thanks to @hamshe bug report.
	 *
	 * @reporter @hamshe
	 * @link https://wordpress.org/support/topic/reference-container-in-elementor/
	 *
	 * @since 2.3.0
	 * @date 2020-12-29T0914+0100
	 * @var int
	 */
	const C_INT_REFERENCE_CONTAINER_TOP_MARGIN    = 'footnotes_inputfield_reference_container_top_margin';

	/**
	 * Settings container key for reference container bottom margin.
	 *
	 * - Bugfix: Reference container: convert top padding to margin and make it a setting, thanks to @hamshe bug report.
	 *
	 * @reporter @hamshe
	 * @link https://wordpress.org/support/topic/reference-container-in-elementor/
	 *
	 * @since 2.3.0
	 * @date 2020-12-29T0914+0100
	 * @var int
	 */
	const C_INT_REFERENCE_CONTAINER_BOTTOM_MARGIN = 'footnotes_inputfield_reference_container_bottom_margin';

	/**
	 * Settings container key to enable hard links.
	 *
	 * @since 2.3.0
	 * @date 2020-12-29T0914+0100
	 * @var str
	 */
	const C_STR_FOOTNOTES_HARD_LINKS_ENABLE       = 'footnotes_inputfield_hard_links_enable';

	/**
	 * Settings container key for hard link anchors in referrers and footnotes.
	 *
	 * @since 2.3.0
	 * @date 2020-12-29T0914+0100
	 * @var str
	 */
	const C_STR_REFERRER_FRAGMENT_ID_SLUG         = 'footnotes_inputfield_referrer_fragment_id_slug';

	/**
	 * Settings container key for hard link anchors in referrers and footnotes.
	 *
	 * @since 2.3.0
	 * @date 2020-12-29T0914+0100
	 * @var str
	 */
	const C_STR_FOOTNOTE_FRAGMENT_ID_SLUG         = 'footnotes_inputfield_footnote_fragment_id_slug';

	/**
	 * Settings container key for hard link anchors in referrers and footnotes.
	 *
	 * @since 2.3.0
	 * @date 2020-12-29T0914+0100
	 * @var str
	 */
	const C_STR_HARD_LINK_IDS_SEPARATOR           = 'footnotes_inputfield_hard_link_ids_separator';

	/**
	 * Settings container key for shortcode syntax validation.
	 *
	 * @since 2.4.0
	 * @date 2021-01-01T0616+0100
	 * @var str
	 */
	const C_STR_FOOTNOTE_SHORTCODE_SYNTAX_VALIDATION_ENABLE = 'footnotes_inputfield_shortcode_syntax_validation_enable';

	/**
	 * Settings container key to enable backlink tooltips.
	 *
	 * - Update: Reference container: Hard backlinks (optional): optional configurable tooltip hinting to use the backbutton instead, thanks to @theroninjedi47 bug report.
	 *
	 * @reporter @theroninjedi47
	 * @link https://wordpress.org/support/topic/hyperlinked-footnotes-creating-excessive-back-history/
	 *
	 * @since 2.5.4
	 * @var str
	 *
	 * When hard links are enabled, clicks on the backlinks are logged in the browsing history,
	 * along with clicks on the referrers.
	 * This tooltip hints to use the backbutton instead, so the history gets streamlined again.
	 * @link https://wordpress.org/support/topic/making-it-amp-compatible/#post-13837359
	 */
	const C_STR_FOOTNOTES_BACKLINK_TOOLTIP_ENABLE = 'footnotes_inputfield_backlink_tooltip_enable';

	/**
	 * Settings container key to configure the backlink tooltip.
	 *
	 * - Update: Reference container: Hard backlinks (optional): optional configurable tooltip hinting to use the backbutton instead, thanks to @theroninjedi47 bug report.
	 *
	 * @reporter @theroninjedi47
	 * @link https://wordpress.org/support/topic/hyperlinked-footnotes-creating-excessive-back-history/
	 *
	 * @since 2.5.4
	 * @var str
	 */
	const C_STR_FOOTNOTES_BACKLINK_TOOLTIP_TEXT = 'footnotes_inputfield_backlink_tooltip_text';

	/**
	 * Settings container key to configure the tooltip excerpt delimiter.
	 *
	 * - Update: Tooltips: ability to display dedicated content before `[[/tooltip]]`, thanks to @jbj2199 issue report.
	 *
	 * The first implementation used a fixed shortcode provided in the changelog.
	 * But Footnotes’ UI design policy is to make shortcodes freely configurable.
	 *
	 * @reporter @jbj2199
	 * @link https://wordpress.org/support/topic/change-tooltip-text/
	 *
	 * @since 2.5.4
	 * @var str
	 *
	 * Tooltips can display another content than the footnote entry
	 * in the reference container. The trigger is a shortcode in
	 * the footnote text separating the tooltip text from the note.
	 * That is consistent with what WordPress does for excerpts.
	 */
	const C_STR_FOOTNOTES_TOOLTIP_EXCERPT_DELIMITER = 'footnotes_inputfield_tooltip_excerpt_delimiter';

	/**
	 * Settings container key to enable mirroring the tooltip excerpt in the reference container.
	 *
	 * @since 2.5.4
	 * @var str
	 *
	 * Tooltips, even jQuery-driven, may be hard to consult on mobiles.
	 * This option allows to read the tooltip content in the reference container too.
	 * @link https://wordpress.org/support/topic/change-tooltip-text/#post-13935050
	 * But this must not be the default behavior.
	 * @link https://wordpress.org/support/topic/change-tooltip-text/#post-13935488
	 */
	const C_STR_FOOTNOTES_TOOLTIP_EXCERPT_MIRROR_ENABLE = 'footnotes_inputfield_tooltip_excerpt_mirror_enable';

	/**
	 * Settings container key to configure the tooltip excerpt separator in the reference container.
	 *
	 * @since 2.5.4
	 * @var str
	 */
	const C_STR_FOOTNOTES_TOOLTIP_EXCERPT_MIRROR_SEPARATOR = 'footnotes_inputfield_tooltip_excerpt_mirror_separator';

	/**
	 * Settings container key to enable superscript style normalization.
	 *
	 * -Bugfix: Referrers: optional fixes to vertical alignment, font size and position (static) for in-theme consistency and cross-theme stability, thanks to @tomturowski bug report.
	 *
	 * @reporter @tomturowski
	 * @link https://wordpress.org/support/topic/in-line-superscript-ref-rides-to-high/
	 *
	 * @since 2.5.4
	 * @var str
	 */
	const C_STR_FOOTNOTE_REFERRERS_NORMAL_SUPERSCRIPT = 'footnotes_inputfield_referrers_normal_superscript';

	/**
	 * Settings container key to select the script mode for the reference container.
	 *
	 * - Bugfix: Reference container: optional alternative expanding and collapsing without jQuery for use with hard links, thanks to @hopper87it @pkverma99 issue reports.
	 *
	 * @reporter @hopper87it
	 * @link https://wordpress.org/support/topic/footnotes-wp-rocket/
	 *
	 * @since 2.5.6
	 * @var str
	 */
	const C_STR_FOOTNOTES_REFERENCE_CONTAINER_SCRIPT_MODE = 'footnotes_inputfield_reference_container_script_mode';

	/**
	 * Settings container key to enable AMP compatibility mode.
	 *
	 * - Adding: Tooltips: make display work purely by style rules for AMP compatibility, thanks to @milindmore22 code contribution.
	 * - Bugfix: Tooltips: enable accessibility by keyboard navigation, thanks to @westonruter code contribution.
	 * - Adding: Reference container: get expanding and collapsing to work also in AMP compatibility mode, thanks to @westonruter code contribution.
	 *
	 * @contributor @milindmore22
	 * @link @link https://github.com/ampproject/amp-wp/issues/5913#issuecomment-785306933
	 *
	 * @contributor @westonruter
	 * @link https://github.com/ampproject/amp-wp/issues/5913#issuecomment-785419655
	 * @link https://github.com/markcheret/footnotes/issues/48#issuecomment-799580854
	 * @link https://github.com/markcheret/footnotes/issues/48#issuecomment-799582394
	 *
	 * @since 2.5.11 (draft)
	 * @var str
	 */
	const C_STR_FOOTNOTES_AMP_COMPATIBILITY_ENABLE = 'footnotes_inputfield_amp_compatibility_enable';

	/**
	 * Settings container key for scroll duration asymmetricity.
	 *
	 * @since 2.5.11
	 * @var str
	 */
	const C_STR_FOOTNOTES_SCROLL_DURATION_ASYMMETRICITY = 'footnotes_inputfield_scroll_duration_asymmetricity';

	/**
	 * Settings container key for scroll down duration.
	 *
	 * @since 2.5.11
	 * @var int
	 */
	const C_INT_FOOTNOTES_SCROLL_DOWN_DURATION = 'footnotes_inputfield_scroll_down_duration';

	/**
	 * Settings container key for scroll down delay.
	 *
	 * @since 2.5.11
	 * @var int
	 */
	const C_INT_FOOTNOTES_SCROLL_DOWN_DELAY = 'footnotes_inputfield_scroll_down_delay';

	/**
	 * Settings container key for scroll up delay.
	 *
	 * @since 2.5.11
	 * @var int
	 */
	const C_INT_FOOTNOTES_SCROLL_UP_DELAY = 'footnotes_inputfield_scroll_up_delay';


	/**
	 *      SETTINGS STORAGE.
	 */

	/**
	 * Stores a singleton reference of this class.
	 *
	 * @since  1.5.0
	 * @var MCI_Footnotes_Settings
	 */
	private static $a_obj_instance = null;

	/**
	 * Contains all Settings Container names.
	 *
	 * @since 1.5.0
	 * @var array
	 *
	 * Edited.
	 * 2.2.2  added tab for Custom CSS  2020-12-15T0740+0100
	 *
	 * These are the storage container names, one per dashboard tab.
	 */
	private $a_arr_container = array(
		'footnotes_storage',
		'footnotes_storage_custom',
		'footnotes_storage_expert',
		'footnotes_storage_custom_css',
	);

	/**
	 * Contains all Default Settings for each Settings Container.
	 *
	 * @since 1.5.0
	 * @var array
	 *
	 * Comments are moved to constant docblocks.
	 */
	private $a_arr_default = array(

		// General settings.
		'footnotes_storage'             => array(

			// AMP compatibility.
			self::C_STR_FOOTNOTES_AMP_COMPATIBILITY_ENABLE            => '',

			// Footnote start and end short codes.
			self::C_STR_FOOTNOTES_SHORT_CODE_START                    => '((',
			self::C_STR_FOOTNOTES_SHORT_CODE_END                      => '))',
			self::C_STR_FOOTNOTES_SHORT_CODE_START_USER_DEFINED       => '',
			self::C_STR_FOOTNOTES_SHORT_CODE_END_USER_DEFINED         => '',
			self::C_STR_FOOTNOTE_SHORTCODE_SYNTAX_VALIDATION_ENABLE   => 'yes',

			// Footnotes numbering.
			self::C_STR_FOOTNOTES_COUNTER_STYLE                       => 'arabic_plain',
			self::C_STR_COMBINE_IDENTICAL_FOOTNOTES                   => 'yes',

			// Scrolling behavior.
			self::C_INT_FOOTNOTES_SCROLL_OFFSET                       => 20,
			self::C_INT_FOOTNOTES_SCROLL_DURATION                     => 380,
			self::C_STR_FOOTNOTES_SCROLL_DURATION_ASYMMETRICITY       => 'no',
			self::C_INT_FOOTNOTES_SCROLL_DOWN_DURATION                => 150,
			self::C_INT_FOOTNOTES_SCROLL_DOWN_DELAY                   => 0,
			self::C_INT_FOOTNOTES_SCROLL_UP_DELAY                     => 0,
			self::C_STR_FOOTNOTES_HARD_LINKS_ENABLE                   => 'no',
			self::C_STR_REFERRER_FRAGMENT_ID_SLUG                     => 'r',
			self::C_STR_FOOTNOTE_FRAGMENT_ID_SLUG                     => 'f',
			self::C_STR_HARD_LINK_IDS_SEPARATOR                       => '+',
			self::C_STR_FOOTNOTES_BACKLINK_TOOLTIP_ENABLE             => 'yes',
			self::C_STR_FOOTNOTES_BACKLINK_TOOLTIP_TEXT               => 'Alt+ ←',

			// Reference container.
			self::C_STR_REFERENCE_CONTAINER_NAME                      => 'References',
			self::C_STR_REFERENCE_CONTAINER_LABEL_ELEMENT             => 'p',
			self::C_STR_REFERENCE_CONTAINER_LABEL_BOTTOM_BORDER       => 'yes',
			self::C_STR_REFERENCE_CONTAINER_COLLAPSE                  => 'no',
			self::C_STR_FOOTNOTES_REFERENCE_CONTAINER_SCRIPT_MODE     => 'jquery',
			self::C_STR_REFERENCE_CONTAINER_POSITION                  => 'post_end',
			self::C_STR_REFERENCE_CONTAINER_POSITION_SHORTCODE        => '[[references]]',
			self::C_STR_REFERENCE_CONTAINER_START_PAGE_ENABLE         => 'yes',
			self::C_INT_REFERENCE_CONTAINER_TOP_MARGIN                => 24,
			self::C_INT_REFERENCE_CONTAINER_BOTTOM_MARGIN             => 0,
			self::C_STR_FOOTNOTES_PAGE_LAYOUT_SUPPORT                 => 'none',
			self::C_STR_FOOTNOTE_URL_WRAP_ENABLED                     => 'yes',
			self::C_STR_REFERENCE_CONTAINER_BACKLINK_SYMBOL_ENABLE    => 'yes',
			self::C_STR_REFERENCE_CONTAINER_BACKLINK_SYMBOL_SWITCH    => 'no',
			self::C_STR_REFERENCE_CONTAINER_3COLUMN_LAYOUT_ENABLE     => 'no',
			self::C_STR_REFERENCE_CONTAINER_ROW_BORDERS_ENABLE        => 'no',

			self::C_STR_BACKLINKS_SEPARATOR_ENABLED                   => 'yes',
			self::C_STR_BACKLINKS_SEPARATOR_OPTION                    => 'comma',
			self::C_STR_BACKLINKS_SEPARATOR_CUSTOM                    => '',

			self::C_STR_BACKLINKS_TERMINATOR_ENABLED                  => 'no',
			self::C_STR_BACKLINKS_TERMINATOR_OPTION                   => 'full_stop',
			self::C_STR_BACKLINKS_TERMINATOR_CUSTOM                   => '',

			self::C_STR_BACKLINKS_COLUMN_WIDTH_ENABLED                => 'no',
			self::C_INT_BACKLINKS_COLUMN_WIDTH_SCALAR                 => '50',
			self::C_STR_BACKLINKS_COLUMN_WIDTH_UNIT                   => 'px',

			self::C_STR_BACKLINKS_COLUMN_MAX_WIDTH_ENABLED            => 'no',
			self::C_INT_BACKLINKS_COLUMN_MAX_WIDTH_SCALAR             => '140',
			self::C_STR_BACKLINKS_COLUMN_MAX_WIDTH_UNIT               => 'px',

			self::C_STR_BACKLINKS_LINE_BREAKS_ENABLED                 => 'no',
			self::C_STR_LINK_ELEMENT_ENABLED                          => 'yes',

			// Footnotes in excerpts.
			self::C_STR_FOOTNOTES_IN_EXCERPT                          => 'no',

			// Footnotes love.
			self::C_STR_FOOTNOTES_LOVE                                => 'no',

			// Deprecated.
			self::C_STR_FOOTNOTES_EXPERT_MODE                         => 'yes',

		),

		// Referrers and tooltips.
		'footnotes_storage_custom'      => array(

			// Backlink symbol.
			self::C_STR_HYPERLINK_ARROW                               => '&#8593;',
			self::C_STR_HYPERLINK_ARROW_USER_DEFINED                  => '',

			// Referrer typesetting and formatting.
			self::C_STR_FOOTNOTES_REFERRER_SUPERSCRIPT_TAGS           => 'yes',
			self::C_STR_FOOTNOTE_REFERRERS_NORMAL_SUPERSCRIPT         => 'no',
			self::C_STR_FOOTNOTES_STYLING_BEFORE                      => '[',
			self::C_STR_FOOTNOTES_STYLING_AFTER                       => ']',

			// Tooltips.
			self::C_STR_FOOTNOTES_MOUSE_OVER_BOX_ENABLED              => 'yes',
			self::C_STR_FOOTNOTES_MOUSE_OVER_BOX_ALTERNATIVE          => 'no',

			// Tooltip position.
			self::C_STR_FOOTNOTES_MOUSE_OVER_BOX_POSITION             => 'top center',
			self::C_STR_FOOTNOTES_ALTERNATIVE_MOUSE_OVER_BOX_POSITION => 'top right',
			self::C_INT_FOOTNOTES_MOUSE_OVER_BOX_OFFSET_X             => 0,
			self::C_INT_FOOTNOTES_ALTERNATIVE_MOUSE_OVER_BOX_OFFSET_X => -50,
			self::C_INT_FOOTNOTES_MOUSE_OVER_BOX_OFFSET_Y             => -7,
			self::C_INT_FOOTNOTES_ALTERNATIVE_MOUSE_OVER_BOX_OFFSET_Y => 24,

			// Tooltip dimensions.
			self::C_INT_FOOTNOTES_MOUSE_OVER_BOX_MAX_WIDTH            => 450,
			self::C_INT_FOOTNOTES_ALTERNATIVE_MOUSE_OVER_BOX_WIDTH    => 400,

			// Tooltip timing.
			self::C_INT_MOUSE_OVER_BOX_FADE_IN_DELAY                  => 0,
			self::C_INT_MOUSE_OVER_BOX_FADE_IN_DURATION               => 200,
			self::C_INT_MOUSE_OVER_BOX_FADE_OUT_DELAY                 => 400,
			self::C_INT_MOUSE_OVER_BOX_FADE_OUT_DURATION              => 200,

			// Tooltip truncation.
			self::C_STR_FOOTNOTES_MOUSE_OVER_BOX_EXCERPT_ENABLED      => 'yes',
			self::C_INT_FOOTNOTES_MOUSE_OVER_BOX_EXCERPT_LENGTH       => 200,
			self::C_STR_FOOTNOTES_TOOLTIP_READON_LABEL                => 'Continue reading',

			// Tooltip text.
			self::C_STR_FOOTNOTES_TOOLTIP_EXCERPT_DELIMITER           => '[[/tooltip]]',
			self::C_STR_FOOTNOTES_TOOLTIP_EXCERPT_MIRROR_ENABLE       => 'no',
			self::C_STR_FOOTNOTES_TOOLTIP_EXCERPT_MIRROR_SEPARATOR    => ' — ',

			// Tooltip appearance.
			self::C_STR_MOUSE_OVER_BOX_FONT_SIZE_ENABLED              => 'yes',
			self::C_FLO_MOUSE_OVER_BOX_FONT_SIZE_SCALAR               => 13,
			self::C_STR_MOUSE_OVER_BOX_FONT_SIZE_UNIT                 => 'px',

			self::C_STR_FOOTNOTES_MOUSE_OVER_BOX_COLOR                => '',
			self::C_STR_FOOTNOTES_MOUSE_OVER_BOX_BACKGROUND           => '',
			self::C_INT_FOOTNOTES_MOUSE_OVER_BOX_BORDER_WIDTH         => 1,
			self::C_STR_FOOTNOTES_MOUSE_OVER_BOX_BORDER_COLOR         => '#cccc99',
			self::C_INT_FOOTNOTES_MOUSE_OVER_BOX_BORDER_RADIUS        => 0,
			self::C_STR_FOOTNOTES_MOUSE_OVER_BOX_SHADOW_COLOR         => '#666666',

			// Your existing Custom CSS code.
			self::C_STR_CUSTOM_CSS                                    => '',

		),

		// Scope and priority
		'footnotes_storage_expert'      => array(

			// WordPress hooks with priority level.
			self::C_STR_EXPERT_LOOKUP_THE_TITLE                       => '',
			self::C_INT_EXPERT_LOOKUP_THE_TITLE_PRIORITY_LEVEL        => PHP_INT_MAX,

			self::C_STR_EXPERT_LOOKUP_THE_CONTENT                     => 'checked',
			self::C_INT_EXPERT_LOOKUP_THE_CONTENT_PRIORITY_LEVEL      => 98,

			self::C_STR_EXPERT_LOOKUP_THE_EXCERPT                     => '',
			self::C_INT_EXPERT_LOOKUP_THE_EXCERPT_PRIORITY_LEVEL      => PHP_INT_MAX,

			self::C_STR_EXPERT_LOOKUP_WIDGET_TITLE                    => '',
			self::C_INT_EXPERT_LOOKUP_WIDGET_TITLE_PRIORITY_LEVEL     => PHP_INT_MAX,

			self::C_STR_EXPERT_LOOKUP_WIDGET_TEXT                     => '',
			self::C_INT_EXPERT_LOOKUP_WIDGET_TEXT_PRIORITY_LEVEL      => 98,

		),

		// Custom CSS.
		'footnotes_storage_custom_css'  => array(

			// Your existing Custom CSS code.
			self::C_STR_CUSTOM_CSS_LEGACY_ENABLE                      => 'yes',

			// Custom CSS.
			self::C_STR_CUSTOM_CSS_NEW                                => '',

		),

	);

	/**
	 * Contains all Settings from each Settings container as soon as this class is initialized.
	 *
	 * @since 1.5.0
	 * @var array
	 */
	private $a_arr_settings = array();

	/**
	 * Class Constructor. Loads all Settings from each WordPress Settings container.
	 *
	 * @since 1.5.0
	 */
	private function __construct() {
		$this->load_all();
	}

	/**
	 * Returns a singleton of this class.
	 *
	 * @since 1.5.0
	 * @return MCI_Footnotes_Settings
	 */
	public static function instance() {
		// No instance defined yet, load it.
		if ( ! self::$a_obj_instance ) {
			self::$a_obj_instance = new self();
		}
		// Return a singleton of this class.
		return self::$a_obj_instance;
	}

	/**
	 * Returns the name of a specified Settings Container.
	 *
	 * @since 1.5.0
	 * @param int $p_int_index Settings Container Array Key Index.
	 * @return str Settings Container name.
	 */
	public function get_container( $p_int_index ) {
		return $this->a_arr_container[ $p_int_index ];
	}

	/**
	 * Returns the default values of a specific Settings Container.
	 *
	 * @since 1.5.6
	 * @param int $p_int_index Settings Container Aray Key Index.
	 * @return array
	 */
	public function get_defaults( $p_int_index ) {
		return $this->a_arr_default[ $this->a_arr_container[ $p_int_index ] ];
	}

	/**
	 * Loads all Settings from each Settings container.
	 *
	 * @since 1.5.0
	 */
	private function load_all() {
		// Clear current settings.
		$this->a_arr_settings = array();
		$num_settings         = count( $this->a_arr_container );
		for ( $i = 0; $i < $num_settings; $i++ ) {
			// Load settings.
			$this->a_arr_settings = array_merge( $this->a_arr_settings, $this->load( $i ) );
		}
	}

	/**
	 * Loads all Settings from specified Settings Container.
	 *
	 * @since 1.5.0
	 * @param int $p_int_index Settings Container Array Key Index.
	 * @return array Settings loaded from Container of Default Settings if Settings Container is empty (first usage).
	 *
	 * @since   ditched trimming whitespace from text box content in response to user request.
	 * @link https://wordpress.org/support/topic/leading-space-in-footnotes-tag/#post-5347966
	 */
	private function load( $p_int_index ) {
		// Load all settings from container.
		$l_arr_options = get_option( $this->get_container( $p_int_index ) );
		// Load all default settings.
		$l_arr_default = $this->a_arr_default[ $this->get_container( $p_int_index ) ];

		// No settings found, set them to their default value.
		if ( empty( $l_arr_options ) ) {
			return $l_arr_default;
		}
		// Iterate through all available settings ( = default values).
		foreach ( $l_arr_default as $l_str_key => $l_str_value ) {
			// Available setting not found in the container.
			if ( ! array_key_exists( $l_str_key, $l_arr_options ) ) {
				// Define the setting with its default value.
				$l_arr_options[ $l_str_key ] = $l_str_value;
			}
		}
		// Iterate through each setting in the container.
		foreach ( $l_arr_options as $l_str_key => $l_str_value ) {
			// Remove all whitespace at the beginning and end of a setting.
			// Trimming whitespace is ditched.
			// $l_str_value = trim($l_str_value);.
			// Write the sanitized value back to the setting container.
			$l_arr_options[ $l_str_key ] = $l_str_value;
		}
		// Return settings loaded from Container.
		return $l_arr_options;
	}

	/**
	 * Updates a whole Settings container.
	 *
	 * @since 1.5.0
	 * @param int   $p_int_index Index of the Settings container.
	 * @param array $p_arr_new_values new Settings.
	 * @return bool
	 */
	public function save_options( $p_int_index, $p_arr_new_values ) {
		if ( update_option( $this->get_container( $p_int_index ), $p_arr_new_values ) ) {
			$this->load_all();
			return true;
		}
		return false;
	}

	/**
	 * Returns the value of specified Settings name.
	 *
	 * @since 1.5.0
	 * @param string $p_str_key Settings Array Key name.
	 * @return mixed Value of the Setting on Success or Null in Settings name is invalid.
	 */
	public function get( $p_str_key ) {
		return array_key_exists( $p_str_key, $this->a_arr_settings ) ? $this->a_arr_settings[ $p_str_key ] : null;
	}

	/**
	 * Deletes each Settings Container and loads the default values for each Settings Container.
	 *
	 * @since 1.5.0
	 *
	 * Edit: This didn’t actually work.
	 * @since 2.2.0 this function is not called any longer when deleting the plugin,
	 * to protect user data against loss, since manually updating a plugin is safer
	 * done by deleting and reinstalling (see the warning about database backup).
	 * 2020-12-13T1353+0100
	 */
	public function clear_all() {
		// Iterate through each Settings Container.
		$num_settings = count( $this->a_arr_container );
		for ( $i = 0; $i < $num_settings; $i++ ) {
			// Delete the settings container.
			delete_option( $this->get_container( $i ) );
		}
		// Set settings back to the default values.
		$this->a_arr_settings = $this->a_arr_default;
	}

	/**
	 * Register all Settings Container for the Plugin Settings Page in the Dashboard.
	 * Settings Container Label will be the same as the Settings Container Name.
	 *
	 * @since 1.5.0
	 */
	public function register_settings() {
		// Register all settings.
		$num_settings = count( $this->a_arr_container );
		for ( $i = 0; $i < $num_settings; $i++ ) {
			register_setting( $this->get_container( $i ), $this->get_container( $i ) );
		}
	}
}
