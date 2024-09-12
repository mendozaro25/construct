window.jj = {
	options: {
		base_url: undefined,
		key: '3p0$',
		site: {},
	},
	init: function(options) {
		this.options = $.extend(this.options, options);
		return this;
	},
	msgAlert: function(message, title, icon) {
		/*return new PNotify({
			title: title,
			text: message,
			type: icon,
			styling: 'bootstrap3'
		});*/
		return notif({
			msg: message,
			type: icon
		});
	},
	msgConfirm: function(message, title, callback) {
		bootbox.confirm({
			message: message,
			title: title,
			buttons: {
			confirm: {
				label: 'Si',
				className: 'btn-success'
			},
			cancel: {
				label: 'No',
				className: 'btn-danger'
			}
			},
			callback: function(result) {
			if (result) {
				if (typeof callback === 'function') {
				callback();
				}
			}
			}
		});
	},

	modalFormCreate: function( params, form_uri, save_uri, callback_opened, callback_success, bx_setting) {
		try {
			if (!(typeof params === 'object' && params !== null)) throw "Parámetros no definidos";

			if (typeof callback_success === "string")
				callback_success = eval(callback_success);  //window[callback_success];

			if (typeof callback_opened === "string")
				callback_opened = eval(callback_opened);  //window[callback_opened];

			$.post(form_uri, params).done(function(h) {
				var box;
				bx_setting = $.extend({
					message: h,
					title: (params.id ? "Editar": "Nuevo") +" Registo",	
					buttons: {
						success: {
							label: "<i class='icon icon-save' ></i> Guardar",
							className: "btn-primary",
							callback: function(){
								var $btn = $(this).find("button.btn-success");
								var $frm = $(this).find("form");

								if ($frm.valid()) {
									$btn.button("loading");
									/*
									$.post(save_uri, $frm.serialize(),
										function(response) {
											if (!response.success) {
												jj.msgAlert("ERROR", "Mensaje", "error" );
												$btn.button("reset");
											} else {
												//bootbox.hideAll();
												box.modal("hide");
												jj.msgAlert("SE REGISTRO CORRECTAMENTE.", "Mensaje", "success" );
												if (typeof callback_success === "function")
													callback_success(response);
											}
										}
									);
									*/

									var formData = new FormData($frm[0]);
									$.ajax({
										url         : save_uri,
										data        : formData,
										cache       : false,
										contentType : false,
										processData : false,
										type        : 'POST',
										success     : function(response){
											if (!response.success) {
												jj.msgAlert("ERROR", "Mensaje", "error" );
												$btn.button("reset");
											} else {
												//bootbox.hideAll();
												box.modal("hide");
												jj.msgAlert("SE REGISTRO CORRECTAMENTE.", "Mensaje", "success" );
												if (typeof callback_success === "function")
													callback_success(response);
											}
										}
									});


								} else {
									let validator = $frm.data("validator");
									if (validator) {
										let ele = validator.invalidElements().eq(0);
										if (ele.length) {
											if ($(ele).closest(".tab-pane").length) {
												let tabId = $(ele).closest(".tab-pane").attr("id");
												$(this).find("a[href='#"+ tabId +"']").tab("show");
											}
											$(ele).focus();
											return false;
										}
									}
								}
								return false;
							}
						},
						cancel: {
							label: "<i class='icon icon-undo' ></i> Cancelar",
							className: "btn-default",
						},
					},
					onEscape: true,
				}, bx_setting);

				box = bootbox.dialog(bx_setting);

				box.on("shown.bs.modal", function(e) {
					let h = $(e.target);
					$(h).find("form").validate(
						{ ignore: [] } //ignore elements hiddens
					);

					if (typeof callback_opened === "function" )
						callback_opened(h);


					init_form();

				});

			}).fail(function(xhr, status, error) {
				var message = "Algo salió mal, intente nuevamente.";
				if(xhr.status==400)
					message = xhr.responseText;
				jj.msgAlert(message, "Mensaje", "warning" );
			});

		} catch (ex) {
			jj.msgAlert(ex, "Mensaje", "warning" );
		}
	},

	modalFormPagoEraser: function( params, form_uri, save_uri, cancel_uri, callback_opened, callback_success, bx_setting) {
		try {
			if (!(typeof params === 'object' && params !== null)) throw "Parámetros no definidos";

			if (typeof callback_success === "string")
				callback_success = eval(callback_success);  //window[callback_success];

			if (typeof callback_opened === "string")
				callback_opened = eval(callback_opened);  //window[callback_opened];

			$.post(form_uri, params).done(function(h) {
				var box;
				bx_setting = $.extend({
					message: h,
					title: "Visualización Pago Borrador",
					buttons: {
						success: {
							label: "<i class='icon icon-save' ></i> Aceptar",
							className: "btn-primary",
							callback: function(){								
								var $btn = $(this).find("button.btn-success");
								var $frm = $(this).find("form");								
								jj.msgConfirm('El pago borrador una vez aceptado, su condición pasara a ACEPTADO y no se podrá actualizar nuevamente. Además, creará un nuevo próximo pago y se cancelará el pago que estas visualizando.', '¿Estás seguro de aceptar este pago borrador?', function() {
									if ($frm.valid()) {
										$btn.button("loading");
	
										var formData = new FormData($frm[0]);
										$.ajax({
											url         : save_uri,
											data        : formData,
											cache       : false,
											contentType : false,
											processData : false,
											type        : 'POST',
											success     : function(response){
												if (!response.success) {
													jj.msgAlert("ESTE PAGO YA FUE PROCESADO.", "Mensaje", "warning" );
													$btn.button("reset");
												} else {
													//bootbox.hideAll();
													box.modal("hide");
													jj.msgAlert("EL PAGO BORRADOR, SE ACEPTO CORRECTAMENTE.", "Mensaje", "success" );
													if (typeof callback_success === "function")
														callback_success(response);
												}
											}
										});
									} else {
										let validator = $frm.data("validator");
										if (validator) {
											let ele = validator.invalidElements().eq(0);
											if (ele.length) {
												if ($(ele).closest(".tab-pane").length) {
													let tabId = $(ele).closest(".tab-pane").attr("id");
													$(this).find("a[href='#"+ tabId +"']").tab("show");
												}
												$(ele).focus();
												return false;
											}
										}
									}									
								});
								return false;
							}
						},
						cancel: {
							label: "<i class='icon icon-save' ></i> Rechazar",
							className: "btn-danger",
							callback: function(){
								var $btn = $(this).find("button.btn-success");
								var $frm = $(this).find("form");							
								jj.msgConfirm('El pago borrador una vez rechazado, su condición pasara a RECHAZADO y no se podrá actualizar nuevamente.', '¿Estás seguro de rechazar este pago borrador?', function() {
									if ($frm.valid()) {
										$btn.button("loading");
										var formData = new FormData($frm[0]);
										$.ajax({
											url         : cancel_uri,
											data        : formData,
											cache       : false,
											contentType : false,
											processData : false,
											type        : 'POST',
											success     : function(response){
												if (!response.success) {
													jj.msgAlert("ESTE PAGO YA FUE PROCESADO.", "Mensaje", "warning" );
													$btn.button("reset");
												} else {
													//bootbox.hideAll();
													box.modal("hide");
													jj.msgAlert("EL PAGO BORRADOR, SE RECHAZADO CORRECTAMENTE.", "Mensaje", "success" );
													if (typeof callback_success === "function")
														callback_success(response);
												}
											}
										});


									} else {
										let validator = $frm.data("validator");
										if (validator) {
											let ele = validator.invalidElements().eq(0);
											if (ele.length) {
												if ($(ele).closest(".tab-pane").length) {
													let tabId = $(ele).closest(".tab-pane").attr("id");
													$(this).find("a[href='#"+ tabId +"']").tab("show");
												}
												$(ele).focus();
												return false;
											}
										}
									}								
								});
								return false;
							}
						},
					},
					onEscape: true,
				}, bx_setting);

				box = bootbox.dialog(bx_setting);

				box.on("shown.bs.modal", function(e) {
					let h = $(e.target);
					$(h).find("form").validate(
						{ ignore: [] } //ignore elements hiddens
					);

					if (typeof callback_opened === "function" )
						callback_opened(h);


					init_form();

				});

			}).fail(function(xhr, status, error) {
				var message = "Algo salió mal, intente nuevamente.";
				if(xhr.status==400)
					message = xhr.responseText;
				jj.msgAlert(message, "Mensaje", "warning" );
			});

		} catch (ex) {
			jj.msgAlert(ex, "Mensaje", "warning" );
		}
	},

	prependToSelect: function(response, idSelect, fieldText, fieldValue) {
		response = response || {};
		console.log(response);
		if (response.success && response.data) {
			if (typeof idSelect === "string")
				idSelect = $(idSelect);

			$(idSelect).prepend(new Option( response.data[fieldText], response.data[fieldValue], true, true));
			$(idSelect).find("option:first").attr("selected", "selected");
		}
	},

	actionRem: function(uri, id, callback) {
		if (typeof callback === "string")
			callback = eval(callback);

		if (!confirm("Desea eliminar este registro?"))
			return false;
		$.post(uri, {id: id} ).done(
			function(data) {
				if (!data.success) {					
					jj.msgAlert(data.message, "Mensaje", "error" );
				} else {								
					jj.msgAlert("EL REGISTRO SE ELIMINO CORRECTAMENTE.", "Mensaje", "success" );
					if (typeof callback === "function" )
						callback(data);
				}
			});
	},

};

$(function() {
	jQuery.extend(jQuery.validator.messages, {
		required: "Este campo es requerido.",
		email: "Por favor, ingrese un correo electrónico válido.",
		number: "Por favor, ingrese un número válido.",
		maxlength: jQuery.validator.format("Por favor, ingrese no más de {0} caracteres."),
	});

	init_form();
});

function init_form() {
	jQuery('input').on("keyup, blur",function() {
		if ($(this).data("not-upper") === undefined && !$(this).hasClass('conf-menu') && !$(this).hasClass('input-password'))
			this.value = this.value.trim().toLocaleUpperCase();
	});
	jQuery('textarea').on("keyup, blur", function() {
		if ($(this).data("not-upper") === undefined && !$(this).hasClass('conf-menu'))
			this.value = this.value.trim().toLocaleUpperCase();
	});

}
