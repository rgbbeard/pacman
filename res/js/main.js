"use strict";
import { SystemFn, $ } from "./utilities.js";
import Request from "./request.js";
import Contextmenu from "./html/contextmenu.js";
import ConfirmDialog from "./html/confirmdialog.js";
import Toast from "./html/toast.js";

SystemFn(function() {
	const 
		toastPosition = "top-right",
		toastTimeout = 5;

	$(".installed-programs .program").each(function(p) {
		p = $(p);

		const d = p?.getIfExists(".program-description").first();

		d?.hide();

		p.on("click", function(t) {
			if(d?.isHidden()) {
				d?.show();
			} else {
				d?.hide();
			}
		});
		
		p.on("contextmenu", function(t) {
			const
				name = t.dataset.name.toLowerCase().trim(),
				version = t.dataset.version.toLowerCase().trim();

			const e = window.event;
			e.preventDefault();

			const m = new Contextmenu({
				title: "Actions",
				voices: {
					1: {
						label: "Safe uninstall",
						click: function() {
							new ConfirmDialog({

							});
							new Request({
								method: "POST",
								url: "api/uninstall.php",
								data: {
									safe: 1,
									name: name,
									version: version
								},
								done: function(r) {
									if(r.code === 200 && r.return === "ok") {
										new Toast({
											text: "Package uninstalled successfully.",
											position: toastPosition,
											timeout: toastTimeout
										});
									} else {
										new Toast({
											text: "There has been an issue uninstalling the package.",
											position: toastPosition,
											timeout: toastTimeout
										});
									}
								}
							});
						}
					},
					2: {
						label: "Force uninstall",
						click: function() {
							new Request({
								method: "POST",
								url: "api/uninstall.php",
								data: {
									safe: 0,
									name: name,
									version: version
								},
								done: function(r) {
									if(r.code === 200 && r.return === "ok") {
										new Toast({
											text: "Package uninstalled successfully.",
											position: toastPosition,
											timeout: toastTimeout
										});
									} else {
										new Toast({
											text: "There has been an issue uninstalling the package.",
											position: toastPosition,
											timeout: toastTimeout
										});
									}
								}
							});
						}
					}
				}
			});

			$("body section").appendChild(m).then(() => {
				Contextmenu.setMenuPos(m);
			});
		});
	});

	$("#search_program").on("keyup", function(s) {
		const value = s.value.toLowerCase().trim();

		$(".installed-programs .program").each(function(p) {
			const 
				name = p.dataset.name.toLowerCase().trim(),
				description = p.dataset.version.toLowerCase().trim();

			if(!value.empty()) {
				if(name.includes(value) || description.includes(value)) {
					p.show();
				} else {
					p.hide();
				}
			} else {
				p.show();
			}
		});
	});
});