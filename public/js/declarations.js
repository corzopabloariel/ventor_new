/**
 * ----------------------------------------
 *              CONSIDERACIONES
 * ---------------------------------------- */
/**
 * Las entidades nombradas a continuación tienen referencia con una tabla de la BASE DE DATOS.
 * Respetar el nombre de las columnas
 *
 * @version 2
 */
const ENTIDADES = {
    slider: {
        TABLE: "sliders",
        ROUTE: "sliders",
        ATRIBUTOS: {
            order: {TIPO:"TP_STRING",LABEL:1,MAXLENGTH:3,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"orden", TH:"70px"},
            image: {TIPO:"TP_IMAGE", EXT: "jpeg, png, jpg, gif", FOLDER: "sliders",RULE: "nullable|mimes:jpeg,png,jpg,gif|max:2048", VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/*",NOMBRE:"Imagen",WIDTH:"auto", HEIGHT:"350px",TH:"300px"},
            section: {TIPO:"TP_STRING",VISIBILIDAD:"TP_INVISIBLE",NOMBRE:"sección"},
            text: {TIPO:"TP_TEXT",EDITOR:1,VISIBILIDAD:"TP_VISIBLE",FIELDSET:1,NOMBRE:"texto"}
        },
        FORM: [
            {
                '/section/<div class="col-12">/image/</div>':['section','image']
            },
            {
                '<div class="col-12 col-md-9">/text/</div><div class="col-12 col-md-3">/order/</div>':['order','text']
            },
        ],
        FUNCIONES: {
            image: {onchange:{F:"readURL(this,'/id/')",C:"id"}}
        },
        EDITOR: {
            text: {
                toolbarGroups: [
                    { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                    { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                    { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
                    { name: 'forms', groups: [ 'forms' ] },
                    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                    { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
                    { name: 'links', groups: [ 'links' ] },
                    { name: 'insert', groups: [ 'insert' ] },
                    { name: 'styles', groups: [ 'styles' ] },
                    { name: 'colors', groups: [ 'colors' ] },
                    { name: 'tools', groups: [ 'tools' ] },
                    { name: 'others', groups: [ 'others' ] },
                    { name: 'about', groups: [ 'about' ] }
                ],
                colorButton_colors : colorPick,
                removeButtons: 'Save,NewPage,Preview,Print,Templates,Cut,Copy,Paste,PasteText,PasteFromWord,Redo,Undo,Find,Replace,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,RemoveFormat,CopyFormatting,NumberedList,BulletedList,Blockquote,CreateDiv,BidiLtr,BidiRtl,Language,Anchor,Unlink,Flash,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe,Styles,Font,Maximize,ShowBlocks,About'
            }
        },
    },
    transport: {
        ATRIBUTOS: {
            code: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",TH:"70px",NOMBRE:"Código", NOTEDIT: 1},
            description: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"Descripción", NOTEDIT: 1},
            address: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"Dirección", NOTEDIT: 1},
            phone: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"Teléfono", NOTEDIT: 1},
            person: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"Responsable", NOTEDIT: 1}
        }
    },
    label: {
        TABLE: "labels",
        ROUTE: "labels",
        ATRIBUTOS: {
            code: {TIPO:"TP_STRING",RULE: "required|max:30",LABEL:1,MAXLENGTH:30,VISIBILIDAD:"TP_VISIBLE",TH:"70px",NOMBRE:"Código", HELP: "Código único", NECESARIO: 1, NOTEDIT: 1},
            data: {TIPO:"TP_STRING",RULE: "required",NECESARIO:1,LABEL:1,VISIBILIDAD:"TP_VISIBLE", NOMBRE: "Etiqueta"}
        },
        FORM: [
            {
                '<div class="col-12 col-md-4">/code/</div><div class="col-12 col-md">/data/</div>' : ["code", "data"]
            }
        ],
    },
    text: {
        TABLE: "texts",
        ROUTE: "texts",
        ATRIBUTOS: {
            code: {TIPO:"TP_STRING",RULE: "required|max:10",LABEL:1,MAXLENGTH:10,VISIBILIDAD:"TP_VISIBLE",TH:"70px",NOMBRE:"Código", HELP: "Código único", NECESARIO: 1, NOTEDIT: 1},
            data: {TIPO:"TP_TEXT",EDITOR:1,VISIBILIDAD:"TP_VISIBLE",FIELDSET:1, NOMBRE:"texto", LABEL: 1}
        },
        FORM: [
            {
                '<div class="col-12 col-md-4">/code/</div>' : ["code"]
            }, {
                '<div class="col-12">/data/</div>' : ["data"]
            }
        ],
        EDITOR: {
            data: {
                toolbarGroups: [
                    { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                    { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                    { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
                    { name: 'forms', groups: [ 'forms' ] },
                    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                    { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
                    { name: 'links', groups: [ 'links' ] },
                    { name: 'insert', groups: [ 'insert' ] },
                    { name: 'styles', groups: [ 'styles' ] },
                    { name: 'colors', groups: [ 'colors' ] },
                    { name: 'tools', groups: [ 'tools' ] },
                    { name: 'others', groups: [ 'others' ] },
                    { name: 'about', groups: [ 'about' ] }
                ],
                colorButton_colors : colorPick,
                removeButtons: 'Save,NewPage,Preview,Print,Templates,Cut,Copy,Paste,PasteText,PasteFromWord,Redo,Undo,Find,Replace,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,RemoveFormat,CopyFormatting,NumberedList,BulletedList,Blockquote,CreateDiv,BidiLtr,BidiRtl,Language,Anchor,Unlink,Flash,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe,Styles,Font,Maximize,ShowBlocks,About'
            }
        },
    },
    operation: {
        TABLE: "operations",
        ROUTE: "operations",
        ATRIBUTOS: {
            code: {TIPO:"TP_STRING",LABEL:1,MAXLENGTH:10,VISIBILIDAD:"TP_VISIBLE_TABLE",TH:"70px",NOMBRE:"Código", NOTEDIT: 1},
            name: {TIPO:"TP_STRING",RULE: "required",NECESARIO:1,LABEL:1,VISIBILIDAD:"TP_VISIBLE", NOMBRE: "Nombre"},
            description: {TIPO:"TP_TEXT",LABEL:1,VISIBILIDAD:"TP_VISIBLE", NOMBRE: "Descripción", NORMAL: 1}
        },
        FORM: [
            {
                '/code/<div class="col-12">/name/</div>' : ["code", "name"]
            }, {
                '<div class="col-12 col-md">/description/</div>' : ["description"]
            }
        ],
    },

    parameter: {
        TABLE: "parameters",
        ROUTE: "parameters",
        ATRIBUTOS: {
            type: {TIPO:"TP_ENUM",RULE: "required",VISIBILIDAD:"TP_VISIBLE",LABEL:1,ENUM:[{id: 'email:notice', text: "Email de avisos del sistema"}, {id: 'email:reply', text: "Email de respuesta"}, {id: 'email:statement', text: "Email de declaraciones"}, {id: 'paginate', text: "Paginado de las entidades"}],NOMBRE:"Tipo",CLASS:"form--input", NECESARIO: 1},
            value: {TIPO:"TP_STRING",RULE: "required|max:150",MAXLENGTH:150,VISIBILIDAD:"TP_VISIBLE",LABEL:1,NOMBRE:"valor", NECESARIO: 1, NOTEDIT: 1}
        },
        FORM: [
            {
                '<div class="col-12 col-md-6">/type/</div><div class="col-12 col-md-6">/value/</div>' : ['value', 'type']
            }
        ]
    },

    client: {
        TABLE: "users",
        ROUTE: "users",
        ATRIBUTOS: {
            comitente: {TIPO:"TP_ENTERO",LABEL:1, VISIBILIDAD:"TP_VISIBLE", NOTEDIT: 1, NOMBRE: "comitente"},
            tipo: {TIPO:"TP_STRING",RULE: "required|max:200",MAXLENGTH:200,NECESARIO:1,LABEL:1,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"tipo", NOTEDIT: 1},
            nombre: {TIPO:"TP_STRING",RULE: "required|max:200",MAXLENGTH:200,NECESARIO:1,LABEL:1,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"nombre", NOTEDIT: 1},
            telefono: {TIPO:"TP_STRING",RULE: "max:150",MAXLENGTH:150,LABEL:1,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"teléfono", NOTEDIT: 1},
            password: {TIPO:"TP_PASSWORD",VISIBILIDAD:"TP_VISIBLE_FORM",LABEL:1,NOMBRE:"contraseña",HELP:"SOLO PARA EDICIÓN - para no cambiar la contraseña, deje el campo vacío"},
            cuit: {TIPO:"TP_ENTERO",LABEL:1, VISIBILIDAD:"TP_VISIBLE", NOTEDIT: 1, NOMBRE: "CUIT"},
            deleted_at: {TIPO:"TP_DELETE",VISIBILIDAD:"TP_VISIBLE_TABLE", NOMBRE: "Estado", OPTION: {true: "Activo", false: "Eliminado"}}
        }
    },
    user: {
        TABLE: "users",
        ROUTE: "users",
        ATRIBUTOS: {
            nombre: {TIPO:"TP_STRING",RULE: "required|max:200",MAXLENGTH:200,NECESARIO:1,LABEL:1,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"nombre"},
            password: {TIPO:"TP_PASSWORD",VISIBILIDAD:"TP_VISIBLE_FORM",LABEL:1,NOMBRE:"contraseña",HELP:"SOLO PARA EDICIÓN - para no cambiar la contraseña, deje el campo vacío"},
            profile: {TIPO:"TP_ENUM",VISIBILIDAD:"TP_VISIBLE",LABEL:1,ENUM:[{id: "root", text: "ROOT"}, {id: "adm", text: "Administrador"}, {id: "user", text: "Usuario"}],NOMBRE:"Tipo",CLASS:"form--input", NECESARIO: 1},
            deleted_at: {TIPO:"TP_DELETE",VISIBILIDAD:"TP_VISIBLE_TABLE", NOMBRE: "Estado", OPTION: {true: "Activo", false: "Eliminado"}}
        },
        FORM: [
            {
                '<div class="col-12">/nombre/</div>' : ['nombre']
            }, {
                '<div class="col-12 col-md-6">/password/</div><div class="col-12 col-md-6">/profile/</div>' : ['password', 'profile']
            }
        ]
    },
    user_email: {
        ONE: 1,
        MULTIPLE: "emails",
        NOMBRE: "Emails",
        ATRIBUTOS: {
            email: {TIPO:"TP_EMAIL", RULE: "required",LABEL:1, NECESARIO: 1,MAXLENGTH:150,VISIBILIDAD:"TP_VISIBLE"}
        },
        FORM: [
            {
                '<div class="col-12">/email/</div>' : ['email']
            }
        ]
    },

    empresa_captcha: {
        ONE: 1,
        NOMBRE: "Google",
        COLUMN: "captcha",
        ATRIBUTOS: {
            public: {TIPO:"TP_STRING", RULE: "required", NECESARIO: 1,LABEL: 1,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"clave pública"},
            private: {TIPO:"TP_STRING", RULE: "required", NECESARIO: 1,LABEL: 1,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"clave secreta"}
        },
        FORM: [
            {
                '<div class="col-12 col-md">/public/</div><div class="col-12 col-md">/private/</div>' : ['public','private']
            }
        ]
    },
    empresa_images: {
        TABLE: "empresa",
        COLUMN: "images",
        ONE: 1,
        NOMBRE: "Imágenes",
        ATRIBUTOS: {
            logo: {TIPO:"TP_IMAGE", EXT: "jpeg, png, jpg, gif", FOLDER: "empresa/logos",RULE: "nullable|mimes:jpeg,png,jpg,gif|max:2048", VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/*",NOMBRE:"Logotipo",WIDTH:"800px", HEIGHT:"210px"},
            favicon: {TIPO:"TP_IMAGE", EXT: "jpeg, png, jpg, gif, ico", FOLDER: "empresa/logos",RULE: "nullable|mimes:jpeg,png,jpg,gif,ico|max:2048", VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/x-icon,image/png",NOMBRE:"favicon",WIDTH:"50px",HEIGHT:"50px"},
        },
        FORM: [
            {
                '<div class="col-12 col-md-8">/logo/</div><div class="col-12 col-md-4">/favicon/</div>' : ['logo','favicon']
            }
        ]
    },
    image: {
        TABLE: "images",
        ROUTE: "images",
        ATRIBUTOS: {
            data: {TIPO:"TP_IMAGE",FOLDER:"miscellaneous", EXT: "jpeg, png, jpg, gif",RULE: "required|image|mimes:jpeg,png,jpg,gif|max:2048",NECESARIO:1,VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/*",NOMBRE:"imagen"},
        },
        FORM: [
            {
                '<div class="col-12 col-md-8">/data/</div>' : ['data']
            }
        ]
    },
};