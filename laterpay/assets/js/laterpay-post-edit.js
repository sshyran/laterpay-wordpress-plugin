(function(e){e(function(){function t(){var t={priceInput:e("#lp_js_post-price-input"),priceTypeInput:e("#lp_js_post-price-type-input"),revenueModel:e("#lp_js_post-revenue-model"),categoryInput:e("#lp_js_post-default-category-input"),priceSection:e("#lp_js_price-type"),pricingTypeButtonGroup:e("#lp_js_price-type-button-group"),pricingTypeButtons:e(".lp_js_price-type-button"),individualPriceButton:e("#lp_js_use-individual-price").parent(),categoryPriceButton:e("#lp_js_use-category-default-price").parent(),globalPriceButton:e("#lp_js_use-global-default-price").parent(),details:e("#lp_js_price-type-details"),detailsSections:e(".lp_js_details-section"),individualPriceDetails:e("#lp_js_individual-price-details"),categoryPriceDetails:e("#lp_js_category-price-details"),categoriesList:e("#lp_js_category-price-details ul"),categories:e("#lp_js_category-price-details li"),dynamicPricingToggle:e("#lp_js_toggle-dynamic-pricing"),dynamicPricingContainer:"#lp_js_dynamic-pricing-widget-container",expanded:"lp_is-expanded",selected:"lp_is-selected",disabled:"lp_is-disabled",dynamicPricingApplied:"lp_is-with-dynamic-pricing",selectedCategory:"lp_selected-category",payPerUse:"ppu",singleSale:"sis"},n=function(){t.pricingTypeButtons.mousedown(function(){r(this)}).click(function(e){e.preventDefault()});e("#post").submit(function(){v()});t.priceInput.keyup(m(function(){i(e(this).val())},800));e("input:radio",t.revenueModel).change(function(){o(t.priceInput.val())});t.dynamicPricingToggle.mousedown(function(){c()}).click(function(e){e.preventDefault()});e(".categorychecklist input:checkbox").on("change",function(){f()});t.categoryPriceDetails.on("mousedown","a",function(){l(this)}).on("click","a",function(e){e.preventDefault()})},r=function(n){var r=e(n),u=r.parent("li"),f=r.attr("id"),l,c;if(u.hasClass(t.disabled)||u.hasClass(t.selected)){return}e("."+t.selected,t.pricingTypeButtonGroup).removeClass(t.selected);u.addClass(t.selected);t.priceSection.removeClass(t.expanded);t.detailsSections.hide();if(f==="lp_js_use-individual-price"){t.priceSection.addClass(t.expanded);t.dynamicPricingToggle.show();t.priceTypeInput.val("individual price");o(t.priceInput.val());if(t.dynamicPricingToggle.text()===lpVars.i18nRemoveDynamicPricing){d();t.individualPriceDetails.show()}}else if(f==="lp_js_use-category-default-price"){a();var h=e(".lp_selected-category a",t.categoriesList);l=h.attr("data-price");c=h.attr("data-revenue-model");i(l);s(c,true);t.priceSection.addClass(t.expanded);t.categoryPriceDetails.show();t.categories.slideDown(250);t.dynamicPricingToggle.hide();t.priceTypeInput.val("category default price")}else if(f==="lp_js_use-global-default-price"){l=r.attr("data-price");c=r.attr("data-revenue-model");i(l);s(c,true);t.dynamicPricingToggle.hide();t.priceTypeInput.val("global default price")}if(f==="lp_js_use-individual-price"&&!t.dynamicPricingToggle.hasClass(t.dynamicPricingApplied)){t.priceInput.removeAttr("disabled");setTimeout(function(){t.priceInput.focus()},50)}else{if(t.dynamicPricingToggle.hasClass(t.dynamicPricingApplied)){p()}t.priceInput.attr("disabled","disabled")}},i=function(e){var n=o(e);t.priceInput.val(n)},s=function(n,r){e("label",t.revenueModel).removeClass(t.selected);if(r){e("input:radio[value!="+n+"]",t.revenueModel).parent("label").addClass(t.disabled)}e("input:radio[value="+n+"]",t.revenueModel).prop("checked","checked").parent("label").removeClass(t.disabled).addClass(t.selected)},o=function(e){e=e.toString().replace(/[^0-9\,\.]/g,"");if(typeof e==="string"&&e.indexOf(",")>-1){e=parseFloat(e.replace(",",".")).toFixed(2)}else{e=parseFloat(e).toFixed(2)}if(isNaN(e)){e=0}e=Math.abs(e);if(e>lpVars.limits.sis_max){e=lpVars.limits.sis_max}else if(e>0&&e<lpVars.limits.ppu_min){e=lpVars.limits.ppu_min}u(e);e=e.toFixed(2);if(lpVars.locale==="de_DE"){e=e.replace(".",",")}return e},u=function(n){var r=e("input:radio:checked",t.revenueModel).val(),i=e("input:radio[value="+t.payPerUse+"]",t.revenueModel),s=e("input:radio[value="+t.singleSale+"]",t.revenueModel);if(n===0||n>=lpVars.limits.ppu_min&&n<lpVars.limits.price_sis_end){i.removeAttr("disabled").parent("label").removeClass(t.disabled)}else{i.attr("disabled","disabled").parent("label").addClass(t.disabled)}if(n>=lpVars.limits.sis_min){s.removeAttr("disabled").parent("label").removeClass(t.disabled)}else{s.attr("disabled","disabled").parent("label").addClass(t.disabled)}if(n>=lpVars.limits.ppusis_max&&r===t.payPerUse){s.prop("checked",true)}else if(n<lpVars.limits.sis_min&&r===t.singleSale){i.prop("checked",true)}e("label",t.revenueModel).removeClass(t.selected);e("input:radio:checked",t.revenueModel).parent("label").addClass(t.selected)},a=function(){var n=t.categoryInput.val(),r=t.categories.first();if(!t.categories.length){t.categoryInput.val("");return}if(typeof n!=="undefined"&&e("[data-category="+n+"]",t.categories.parent()).length){e("[data-category="+n+"]",t.categories.parent()).addClass(t.selectedCategory)}else{r.addClass(t.selectedCategory);t.categoryInput.val(r.data("category"))}if(t.categoryPriceButton.hasClass(t.selected)){var o=e(".lp_selected-category a",t.categoriesList),u=o.attr("data-price"),a=o.attr("data-revenue-model");i(u);s(a,true)}},f=function(){var n=e("#categorychecklist :checkbox:checked"),r=n.length,o=[],u="",f,l;for(f=0;f<r;f++){l=parseInt(n.eq(f).val(),10);o.push(l)}e.post(lpVars.ajaxUrl,{action:"laterpay_get_category_prices",form:"laterpay_get_category_prices",category_ids:o},function(n){if(n){n.forEach(function(e){u+='<li data-category="'+e.category_id+'">'+'<a href="#" data-price="'+e.category_price+'" data-revenue-model="'+e.revenue_model+'">'+"<span>"+parseFloat(e.category_price).toFixed(2)+" "+lpVars.currency+"</span>"+e.category_name+"</a>"+"</li>"});t.categoriesList.html(u);if(n.length){t.categoryPriceButton.removeClass(t.disabled);t.categories=e("#lp_js_category-price-details li");a()}else{t.categoryPriceButton.addClass(t.disabled);t.detailsSections.hide();if(t.categoryPriceButton.hasClass(t.selected)){e("."+t.selected,t.pricingTypeButtonGroup).removeClass(t.selected);t.priceSection.removeClass(t.expanded);if(t.globalPriceButton.hasClass(t.disabled)){t.individualPriceButton.addClass(t.selected);t.priceTypeInput.val("individual price");t.dynamicPricingToggle.show();t.priceInput.removeAttr("disabled");i(0);s(t.payPerUse,false)}else{t.globalPriceButton.addClass(t.selected);t.priceTypeInput.val("global default price");i(lpVars.globalDefaultPrice);s(e("a",t.globalPriceButton).attr("data-revenue-model"),true)}}}}},"json")},l=function(n){var r=e(n),o=r.parent(),u=o.attr("data-category"),a=r.attr("data-price"),f=r.attr("data-revenue-model");t.categories.removeClass(t.selectedCategory);o.addClass(t.selectedCategory);t.categoryInput.val(u);i(a);s(f,true)},c=function(){if(t.dynamicPricingToggle.hasClass(t.dynamicPricingApplied)){p();t.revenueModel.show()}else{h()}},h=function(){d();t.dynamicPricingToggle.addClass(t.dynamicPricingApplied);t.priceInput.attr("disabled","disabled");t.individualPriceDetails.slideDown(250);t.priceTypeInput.val("individual price, dynamic");t.dynamicPricingToggle.text(lpVars.i18nRemoveDynamicPricing);t.revenueModel.hide()},p=function(){t.dynamicPricingToggle.removeClass(t.dynamicPricingApplied);t.priceInput.removeAttr("disabled");t.individualPriceDetails.slideUp(250,function(){e(t.dynamicPricingContainer).empty()});t.priceTypeInput.val("individual price");t.dynamicPricingToggle.text(lpVars.i18nAddDynamicPricing)},d=function(){var e=lpVars.dynamicPricingData,n=new LPCurve(t.dynamicPricingContainer),r=lpVars.dynamicPricingData[0].y,i=lpVars.dynamicPricingData[3].y,s=0,o=5;window.lpc=n;t.priceInput.attr("disabled","disabled");if(r>lpVars.limits.ppusis_max||i>lpVars.limits.ppusis_max){o=lpVars.limits.sis_max}else if(r>=lpVars.limits.sis_min||i>=lpVars.limits.sis_min){o=lpVars.limits.ppusis_max}else{o=lpVars.limits.ppu_max}if(e.length===4){n.set_data(e).setPrice(s,o,lpVars.globalDefaultPrice).plot()}else{n.set_data(e).setPrice(s,o,lpVars.globalDefaultPrice).interpolate("step-before").plot()}},v=function(){if(!t.dynamicPricingToggle.hasClass(t.dynamicPricingApplied)){return}var n=window.lpc.get_data();if(window.lpc.get_data().length===4){e("input[name=laterpay_start_price]").val(n[0].y);e("input[name=laterpay_end_price]").val(n[3].y);e("input[name=laterpay_change_start_price_after_days]").val(n[1].x);e("input[name=laterpay_transitional_period_end_after_days]").val(n[2].x);e("input[name=laterpay_reach_end_price_after_days]").val(n[3].x)}else if(window.lpc.get_data().length===3){e("input[name=laterpay_start_price]").val(n[0].y);e("input[name=laterpay_end_price]").val(n[2].y);e("input[name=laterpay_change_start_price_after_days]").val(n[1].x);e("input[name=laterpay_transitional_period_end_after_days]").val(0);e("input[name=laterpay_reach_end_price_after_days]").val(n[2].x)}return true},m=function(e,t){var n=undefined;return function(){var r=this,i=arguments;clearTimeout(n);n=setTimeout(function(){e.apply(r,i)},t)}},g=function(){n();if(t.dynamicPricingToggle.hasClass(t.dynamicPricingApplied)){d()}};g()}t()})})(jQuery)