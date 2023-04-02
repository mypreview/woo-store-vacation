"use strict";(function(wp){"use strict";if(!wp){return}var el=wp.element.createElement;var registerBlockType=wp.blocks.registerBlockType;var Notice=wp.components.Notice;var __=wp.i18n.__;registerBlockType("mypreview/woo-store-vacation",{title:__("Store Vacation Notice","woo-store-vacation"),description:__("Placeholder block for displaying store vacation notice.","woo-store-vacation"),icon:{foreground:"#7f54b3",src:"palmtree"},category:"woocommerce",supports:{html:false},keywords:[__("shortcode","woo-store-vacation")],edit:function edit(){return el(Notice,{className:"wc-blocks-sidebar-compatibility-notice is-dismissible",isDismissible:false,status:"warning"},__("\u26A0 This alert-box is a placeholder that is displayed in place of the actual vacation notice message.","woo-store-vacation"))},save:function save(){return"[woo_store_vacation]"}})})(window.wp);