"use strict";
import { SystemFn, $ } from "./lib/utilities.js";
import Request from "./lib/request.js";
import Contextmenu from "./lib/html/contextmenu.js";
import ConfirmDialog from "./lib/html/confirmdialog.js";
import Toast from "./lib/html/toast.js";

SystemFn(function() {
	const 
		params = new URLSearchParams(window.location.search),
		toastPosition = "top-right",
		toastTimeout = 5;

	// custom menu
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
						label: "Update",
						click: function() {
							new Request({
								method: "POST",
								url: "api/update.php",
								data: {
									name: name
								},
								done: function(r) {
									if(r.code === 200 && r.return === "ok") {
										new Toast({
											text: "Package updated successfully.",
											appearance: "success",
											position: toastPosition,
											timeout: toastTimeout
										});
									} else {
										new Toast({
											text: "There was an issue updating the package.",
											appearance: "error",
											position: toastPosition,
											timeout: toastTimeout
										});
									}
								}
							});
						}
					},
					2: {
						label: "Update and fix broken deps",
						click: function() {
							new Request({
								method: "POST",
								url: "api/update.php",
								data: {
									name: name,
									fix_broken: 77
								},
								done: function(r) {
									if(r.code === 200 && r.return === "ok") {
										new Toast({
											text: "Package updated successfully.",
											appearance: "success",
											position: toastPosition,
											timeout: toastTimeout
										});
									} else {
										new Toast({
											text: "There was an issue updating the package.",
											appearance: "error",
											position: toastPosition,
											timeout: toastTimeout
										});
									}
								}
							});
						}
					},
					3: {
						label: "Safe uninstall",
						click: function() {
							new Request({
								method: "POST",
								url: "api/uninstall.php",
								data: {
									name: name
								},
								done: function(r) {
									if(r.code === 200 && r.return === "ok") {
										new Toast({
											text: "Package uninstalled successfully.",
											appearance: "success",
											position: toastPosition,
											timeout: toastTimeout
										});
									} else {
										new Toast({
											text: "There was an issue uninstalling the package.",
											appearance: "error",
											position: toastPosition,
											timeout: toastTimeout
										});
									}
								}
							});
						}
					},
					4: {
						label: "Force uninstall",
						click: function() {
							new Request({
								method: "POST",
								url: "api/uninstall.php",
								data: {
									force: 1,
									name: name
								},
								done: function(r) {
									if(r.code === 200 && r.return === "ok") {
										new Toast({
											text: "Package uninstalled successfully.",
											appearance: "success",
											position: toastPosition,
											timeout: toastTimeout
										});
									} else {
										new Toast({
											text: "There was an issue uninstalling the package.",
											appearance: "error",
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

	// filters
	$("#filter-remove").on("click", function(s) {
		$(".installed-programs .program").each(function(p) {
			p.show();
		});
	});

	$("#filter-only-upgradable").on("click", function(s) {
		$(".installed-programs .program").each(function(p) {
			const title = $(p).getIfExists("b").value().toLowerCase().trim()

			if(title.includes("upgradable")) {
				p.show();
			} else {
				p.hide();
			}
		});
	});

	$("#search_program").on("keyup", function(s) {
		const value = s.value.toLowerCase().trim();

		$(".installed-programs .program").each(function(p) {
			const
				title = $(p).getIfExists("b").value().toLowerCase().trim(),
				name = p.dataset.name.toLowerCase().trim(),
				description = p.dataset.version.toLowerCase().trim();

			if(!value.empty()) {
				if(title.includes(value) || name.includes(value) || description.includes(value)) {
					p.show();
				} else {
					p.hide();
				}
			} else {
				p.show();
			}
		});
	});

	$("#search_program").trigger("keyup");
});