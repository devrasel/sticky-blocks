===  Sticky Sidebar for Ads and Blocks ===
Contributors: wprasel
Donate link: https://www.webextended.com/
Tags: sticky blocks, sticky anything, sticky sidebar, sidebar sticky, sticky widget, widget, sticky ads
Requires at least: 5.6
Tested up to: 6.8.2
Stable tag: 1.0.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html



== Description ==

Make a sticky sidebar or widget or any block you want for any ads or Google adsense just adding the class or ids from the sticky section. You can add multiple sticky block within same page or post even any custom post and taxonomy as well.



== Manage Sticky Blocks == 

In wp dashboard , there is a menu called **"Sticky Blocks & Sticky Ads"** to Add and Remove a Sticky Block from there.
You have to put the four different selector from settings
- Container or whole conatiner wrapper class or id
- Columns class or IDs if you have multiple column then select two columns only
- Add class or id from sticky block or section or sidebar section
- [b] Please make sure you have proper selectors to make the block sticky.

That's it done! for more support and information you can contact us [Support Here](https://www.webextended.com/contact/)


== Installation ==

1. Add plugin to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use Sticky Blocks from settings to set the selectors


== Screenshots ==

1. Backend view
2. Frontend view


== Frequently Asked Questions == 

= How to use this plugin?
First create a sticky Blocks(Through Sticky Blocks) calling it through selectors.

= How to manage sticky sidebars?
Go here : Dashboard >> Sticky Blocks

= Is it possible to display the sticky sidebars in a page or a template?
Yes,  you can add it out anywhere you want! even in custom post type template!

= Is it possible to display multiple sticky sidebars in a page or a template?
Yes you can.

= Is it possible to add some styles to the element but only when it's sticky?
Yes you can. Just add css as usual you do using class or ids from the sticky block

= My sticky element stops scrolling too early. Why?
A sticky element can only scroll within the boundaries of its direct parent container. If your sticky element stops, it's because you've reached the bottom of its parent. To make it scroll longer, you need to ensure the parent container is tall enough to accommodate the desired scrolling distance. This is a fundamental rule of how the CSS `position: sticky` property works.

== Changelog ==
= 1.0.5 =
* August 26, 2025
* Enhancement: Moved "Custom CSS" to its own submenu page for a cleaner admin interface.
* Fix: Corrected a data type mismatch that prevented the "Specific Page ID" display option from working.
* Fix: Implemented a JavaScript solution to ensure nested sticky elements can scroll within the full height of the parent column by dynamically adjusting container height.

= 1.0.4 =
* August 25, 2025
* Fix: Custom CSS is now correctly loaded on the frontend.
* Fix: Resolved issue where `position: sticky` was blocked by parent elements with `overflow: hidden`.
* Fix: Saving settings without changes now shows a success message instead of an error.
* Enhancement: Admin table now shows specific display rules (IDs or URL parts) for better clarity.
* Enhancement: Improved admin notices for saving and updating sticky blocks.

= 1.0.2 =
* July 10, 2023
* Updated minor issues, compatibility, WP version

= 1.0.1 =
* Dec 03, 2022
* Updated minor issues, compatibility, best user experience
* Optimized functions


= 1.0.0 =
* Sept 01, 2022
* Built this plugin
