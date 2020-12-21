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
        ADD: 1,
        BTN: ['d', 'e'],
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
        ROUTE: "transports",
        ADD: 0,
        ATRIBUTOS: {
            code: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",TH:"70px",NOMBRE:"Código", NOTEDIT: 1},
            description: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"Descripción", NOTEDIT: 1},
            address: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"Dirección", NOTEDIT: 1},
            phone: {TIPO:"TP_PHONE",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"Teléfono", NOTEDIT: 1},
            person: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"Responsable", NOTEDIT: 1}
        }
    },
    employee: {
        TABLE: "users",
        ROUTE: "employees",
        ADD: 0,
        ATRIBUTOS: {
            docket: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",TH:"140px",NOMBRE:"Legajo", NOTEDIT: 1},
            name: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"Nombre"},
            username: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"Usuario", NOTEDIT: 1},
            email: {TIPO:"TP_EMAIL",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"E-mail", NOTEDIT: 1}
        }
    },
    seller: {
        ROUTE: "sellers",
        ADD: 0,
        ATRIBUTOS: {
            docket: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",TH:"140px",NOMBRE:"Legajo", NOTEDIT: 1},
            name: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"Nombre", NOTEDIT: 1},
            username: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"Usuario", NOTEDIT: 1},
            phone: {TIPO:"TP_PHONE",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"Teléfono", NOTEDIT: 1},
            email: {TIPO:"TP_EMAIL",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"E-mail", NOTEDIT: 1}
        }
    },
    client: {
        TABLE: "clients",
        ROUTE: "clients",
        ADD: 0,
        BTN: ['p'],
        ATRIBUTOS: {
            nrocta: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",TH:"50px",NOMBRE:"Cuenta", NOTEDIT: 1},
            nrodoc: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"Documento", NOTEDIT: 1},
            razon_social: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"Razón Social", NOTEDIT: 1},
            telefn: {TIPO:"TP_PHONE",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"Teléfono", NOTEDIT: 1},
            direml: {TIPO:"TP_EMAIL",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"Email", NOTEDIT: 1},
        }
    },
    new: {
        TABLE: "news",
        ROUTE: "news",
        ADD: 1,
        BTN: ['d', 'e'],
        ATRIBUTOS: {
            name: {TIPO:"TP_STRING",RULE: "required|max:150",MAXLENGTH:150,NECESARIO:1,LABEL:1,VISIBILIDAD:"TP_VISIBLE", NOMBRE: "Nombre",TH:"500px"},
            image: {TIPO:"TP_IMAGE", EXT: "jpeg, png, jpg, gif", FOLDER: "news",RULE: "nullable|mimes:jpeg,png,jpg,gif|max:2048", VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/*",NOMBRE:"Imagen",WIDTH:"auto", HEIGHT:"300px",TH:"300px"},
            file: {TIPO:"TP_FILE", EXT: "pdf, xls, txt, bdf", FOLDER: "news",RULE: "nullable|mimes:pdf,xls,txt,bdf|max:2048", VISIBILIDAD:"TP_VISIBLE",ACCEPT:"application/pdf, application/vnd.ms-excel, text/plain, .dbf",NOMBRE:"Archivo"},
        },
        FORM: [
            {
                '<div class="col-12 col-md-6">/image/</div><div class="col-12 col-md-6"><div class="row"><div class="col-12">/file/</div><div class="col-12 mt-4">/name/</div></div></div>' : ["image", "file", "name"]
            }
        ]
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
    empresa_direccion: {
        ONE: 1,
        NOMBRE: "Domicilio",
        COLUMN: "address",
        ATRIBUTOS: {
            calle: {TIPO:"TP_STRING",LABEL:1,VISIBILIDAD:"TP_VISIBLE", RULE: "required|max:200"},
            altura: {TIPO:"TP_STRING",LABEL:1,VISIBILIDAD:"TP_VISIBLE", RULE: "required"},
            localidad: {TIPO:"TP_STRING",LABEL:1,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"localidad", RULE: "required|max:200"},
            provincia: {TIPO:"TP_STRING",LABEL:1,VISIBILIDAD:"TP_VISIBLE",DEFAULT:"Buenos Aires", RULE: "required|max:200"},
            pais: {TIPO:"TP_STRING",LABEL:1,VISIBILIDAD:"TP_VISIBLE",DEFAULT:"Argentina",NOMBRE:"país"},
            cp: {TIPO:"TP_STRING",LABEL:1,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"código postal", RULE: "required"},
            mapa: {TIPO:"TP_TEXT", NORMAL: 1,LABEL:1,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"ubicación de Google Maps"},
            link: {TIPO:"TP_TEXT", NORMAL: 1,LABEL:1,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"enlace de Google Maps"}
        },
        FORM: [
            {
                '<div class="col-12 col-md-8">/calle/</div><div class="col-12 col-md-4">/altura/</div>' : ['calle','altura'],
            },
            {
                '<div class="col-12 col-md-6">/cp/</div><div class="col-12 col-md-6">/pais/</div>' : ['cp','pais']
            },
            {
                '<div class="col-12 col-md-6">/provincia/</div><div class="col-12 col-md-6">/localidad/</div>' : ['provincia','localidad']
            },
            {
                '<div class="col-12"><div class="alert alert-primary" role="alert">Copie de <a class="text-dark" href="https://www.google.com/maps" target="blank"><strong>Google Maps</strong> <i class="fas fa-external-link-alt"></i></a> la ubicación de la Empresa <i class="fas fa-share-alt"></i> / Insertar mapa / iFrame</div>/mapa/</div>' : ['mapa']
            },
            {
                '<div class="col-12"><div class="alert alert-warning" role="alert">Copie de <a class="text-dark" href="https://www.google.com/maps" target="blank"><strong>Google Maps</strong> <i class="fas fa-external-link-alt"></i></a> la ubicación de la Empresa <i class="fas fa-share-alt"></i> / Enlace para compartir</div>/link/</div>' : ['link']
            }
        ]
    },
    empresa_images: {
        TABLE: "empresa",
        COLUMN: "images",   
        ONE: 1,
        NOMBRE: "Imágenes",
        ATRIBUTOS: {
            logo: {TIPO:"TP_IMAGE", EXT: "jpeg, png, jpg, gif", FOLDER: "empresa/logos",RULE: "nullable|mimes:jpeg,png,jpg,gif|max:2048", VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/*",NOMBRE:"Logo",WIDTH:"254px", HEIGHT:"65px"},
            logo_footer: {TIPO:"TP_IMAGE", EXT: "jpeg, png, jpg, gif", FOLDER: "empresa/logos",RULE: "nullable|mimes:jpeg,png,jpg,gif|max:2048", VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/*",NOMBRE:"Logo Pie",WIDTH:"254px", HEIGHT:"65px"},
            favicon: {TIPO:"TP_IMAGE", EXT: "jpeg, png, jpg, gif, ico", FOLDER: "empresa/logos",RULE: "nullable|mimes:jpeg,png,jpg,gif,ico|max:2048", VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/x-icon,image/png",NOMBRE:"favicon",WIDTH:"50px",HEIGHT:"50px"},
        },
        FORM: [
            {
                '<div class="col-12 col-md-4">/logo/</div><div class="col-12 col-md-4">/logo_footer/</div><div class="col-12 col-md-4">/favicon/</div>' : ['logo', 'logo_footer', 'favicon']
            }
        ]
    },
    empresa_email: {
        ONE: 1,
        MULTIPLE: "Email",
        FUNCTION: "email",
        NOMBRE: "Emails",
        COLUMN: "email",
        ATRIBUTOS: {
            email: {TIPO:"TP_EMAIL",LABEL:1,MAXLENGTH:150,VISIBILIDAD:"TP_VISIBLE"}
        },
        FORM: [
            {
                '<div class="col-12">/email/</div>' : ['email']
            }
        ]
    },
    empresa_telefono: {
        ONE: 1,
        MULTIPLE: "Teléfono",
        FUNCTION: "phone",
        NOMBRE: "Teléfonos de contacto",
        COLUMN: "phone",
        ATRIBUTOS: {
            telefono: {TIPO:"TP_PHONE",LABEL:1,MAXLENGTH:50,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"número",HELP:"Contenido oculto en el HREF. Solo números"},
            tipo: {TIPO:"TP_ENUM",ENUM:[{id: "tel", text: "Teléfono/Celular"}, {id: "wha", text: "Whatsapp"}],NECESARIO:1,VISIBILIDAD:"TP_VISIBLE_FORM",NOMBRE:"Tipo",NORMAL: 1, LABEL: 1},
            visible: {TIPO:"TP_STRING",LABEL:1,MAXLENGTH:50,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"elemento visible",HELP:"Contenido visible. En caso de permanecer vacío, se utilizará el primer campo"},
            is_link: {TIPO:"TP_CHECK",VISIBILIDAD:"TP_VISIBLE",CHECK:"¿Es clickeable?"},
        },
        FORM: [
            {
                '<div class="col-12">/tipo/</div><div class="col-12 mt-3">/telefono/</div>' : ['tipo','telefono']
            },
            {
                '<div class="col-12">/visible/</div>':['visible']
            },
            {
                '<div class="col-12 d-flex justify-content-between">/is_link/</div>':['is_link']
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