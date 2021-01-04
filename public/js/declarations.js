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
        BTN: ['p'],
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
            //docket: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",TH:"140px",NOMBRE:"Legajo", NOTEDIT: 1},
            dockets: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",TH:"140px",NOMBRE:"Legajos", NOTEDIT: 1},
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
    product: {
        TABLE: "products",
        ROUTE: "products",
        ADD: 0,
        ATRIBUTOS: {
            stmpdh_art: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",TH:"140px",NOMBRE:"Código", NOTEDIT: 1},
            stmpdh_tex: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"Nombre", NOTEDIT: 1},
            precio: {TIPO:"TP_MONEY",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"Precio", NOTEDIT: 1},
            parte: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"Parte", NOTEDIT: 1},
            subparte: {TIPO:"TP_JSON", STRING: "/code/ - /name/", ATTR: ["code", "name"],VISIBILIDAD:"TP_VISIBLE",NOMBRE:"subparte", NOTEDIT: 1},
            fecha_ingr: {TIPO:"TP_DATE",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"Ingreso", NOTEDIT: 1}
        }
    },
    new: {
        TABLE: "news",
        ROUTE: "news",
        ADD: 1,
        BTN: ['d', 'e'],
        ATRIBUTOS: {
            name: {TIPO:"TP_STRING",RULE: "required|max:150",MAXLENGTH:150,NECESARIO:1,LABEL:1,VISIBILIDAD:"TP_VISIBLE", NOMBRE: "Nombre",TH:"500px"},
            image: {TIPO:"TP_IMAGE", EXT: "jpeg, png, jpg, gif", FOLDER: "news",RULE: "nullable|mimes:jpeg,png,jpg,gif", VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/*",NOMBRE:"Imagen",WIDTH:"auto", HEIGHT:"300px",TH:"300px"},
            file: {TIPO:"TP_FILE", EXT: "pdf, xls, txt, bdf", FOLDER: "news",RULE: "nullable|mimes:pdf,xls,txt,bdf", VISIBILIDAD:"TP_VISIBLE",ACCEPT:"application/pdf, application/vnd.ms-excel, text/plain, .dbf",NOMBRE:"Archivo"},
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
    empresa_form: {
        ONE: 1,
        MULTIPLE: "Formulario",
        FUNCTION: "forms",
        NOMBRE: "Formularios",
        COLUMN: "forms",
        ATRIBUTOS: {
            form: {TIPO:"TP_ENUM",LABEL:1,ENUM:[{"id": "transmision", "text": "Análisis de transmisión"}, {"id": "pagos", "text": "Información sobre pagos"}, {"id": "consulta", "text": "Consulta general"}, {"id": "contacto", "text": "Contacto"},{"id": "datos", "text": "Actualización de datos"}],NECESARIO:1,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"Formulario",NORMAL:1},
            email: {TIPO:"TP_EMAIL",LABEL:1,MAXLENGTH:150,VISIBILIDAD:"TP_VISIBLE"}
        },
        FORM: [
            {
                '<div class="col-12">/form/</div>' : ['form']
            },
            {
                '<div class="col-12">/email/</div>' : ['email']
            }
        ]
    },
    empresa_social: {
        ONE: 1,
        MULTIPLE: "Red social",
        FUNCTION: "social",
        NOMBRE: "Redes sociales",
        COLUMN: "social",
        ATRIBUTOS: {
            redes: {TIPO:"TP_ENUM",LABEL:1,ENUM:[{"id": "facebook", "text": "Facebook"}, {"id": "instagram", "text": "Instagram"}, {"id": "twitter", "text": "Twitter"}, {"id": "youtube", "text": "Youtube"},{"id": "linkedin", "text": "LinkedIn"}],NECESARIO:1,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"red social",NORMAL:1},
            titulo: {TIPO:"TP_STRING",LABEL: 1,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"título"},
            url: {TIPO:"TP_LINK",LABEL: 1,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"link del sitio"},
        },
        FORM: [
            {
                '<div class="col-12">/redes/</div>' : ['redes']
            },
            {
                '<div class="col-12">/titulo/</div>' : ['titulo']
            },
            {
                '<div class="col-12">/url/</div>': ['url']
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


    /**********************************
            EMPRESA
     ********************************** */
    empresa: {
        ATRIBUTOS: {
            texto: {TIPO:"TP_TEXT",EDITOR:1,LABEL:1,VISIBILIDAD:"TP_VISIBLE",FIELDSET:1,NOMBRE:"texto"},
        },
        FORM: [
            {
                '<div class="col-12">/texto/</div>' : ['texto']
            }
        ],
        EDITOR: {
            texto: {
                toolbarGroups: [
                    { name: "basicstyles", groups: ["basicstyles"] },
                    { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
                    { name: 'links' },
                    { name: 'colors', groups: [ 'TextColor', 'BGColor' ] },
                ],
                colorButton_colors : colorPick,
                removeButtons: 'CreateDiv,Language',
                height: '300px'
            }
        }
    },
    empresa_mision: {
        TABLE: "empresa",
        COLUMN: "mision",   
        ONE: 1,
        NOMBRE: "Misión",
        ATRIBUTOS: {
            titulo: {TIPO:"TP_STRING",LABEL:1,MAXLENGTH:70,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"título"},
            texto:  {TIPO:"TP_TEXT",EDITOR:1,VISIBILIDAD:"TP_VISIBLE",FIELDSET:1,NOMBRE:"texto"}
        },
        FORM: [
            {
                '<div class="col-12">/titulo/</div>' : ['titulo']
            },
            {
                '<div class="col-12">/texto/</div>' : ['texto']
            }
        ],
        EDITOR: {
            texto: {
                toolbarGroups: [
                    { name: "basicstyles", groups: ["basicstyles"] },
                    { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
                    { name: 'links' },
                    { name: 'colors', groups: [ 'TextColor', 'BGColor' ] },
                ],
                colorButton_colors : colorPick,
                removeButtons: 'CreateDiv,Language',
                height: '270px'
            }
        }
    },
    empresa_vision: {
        TABLE: "empresa",
        COLUMN: "vision",   
        ONE: 1,
        NOMBRE: "Visión",
        ATRIBUTOS: {
            titulo: {TIPO:"TP_STRING",MAXLENGTH:70,LABEL:1,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"título"},
            texto:  {TIPO:"TP_TEXT",EDITOR:1,VISIBILIDAD:"TP_VISIBLE",FIELDSET:1,NOMBRE:"texto"},
        },
        FORM: [
            {
                '<div class="col-12">/titulo/</div>' : ['titulo']
            },
            {
                '<div class="col-12">/texto/</div>' : ['texto']
            }
        ],
        EDITOR: {
            texto: {
                toolbarGroups: [
                    { name: "basicstyles", groups: ["basicstyles"] },
                    { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
                    { name: 'links' },
                    { name: 'colors', groups: [ 'TextColor', 'BGColor' ] },
                ],
                colorButton_colors : colorPick,
                removeButtons: 'CreateDiv,Language',
                height: '270px'
            }
        }
    },
    empresa_numero: {
        ONE: 1,
        MULTIPLE: "Número",
        FUNCTION: "number",
        NOMBRE: "Números",
        COLUMN: "number",
        ATRIBUTOS: {
            order: {TIPO:"TP_ENTERO",LABEL:1,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"orden", SORTEABLE: 1},
            numero: {TIPO:"TP_STRING",LABEL:1,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"número"},
            texto:  {TIPO:"TP_TEXT",EDITOR:1,VISIBILIDAD:"TP_VISIBLE",FIELDSET:1,NOMBRE:"texto"}
        },
        FORM: [
            {
                '<div class="col-12 col-md-6">/order/</div>' : ['order']
            },
            {
                '<div class="col-12">/numero/</div>' : ['numero']
            },
            {
                '<div class="col-12">/texto/</div>' : ['texto']
            }
        ],
        EDITOR: {
            texto: {
                toolbarGroups: [
                    { name: "basicstyles", groups: ["basicstyles"] },
                    { name: 'colors', groups: [ 'TextColor', 'BGColor' ] },
                ],
                colorButton_colors : colorPick,
                removeButtons: 'CreateDiv,Language',
                height: '50px'
            }
        }
    },
    empresa_anio: {
        ONE: 1,
        MULTIPLE: "Año",
        FUNCTION: "year",
        NOMBRE: "Años",
        COLUMN: "year",
        ATRIBUTOS: {
            order:  {TIPO:"TP_ENTERO", LABEL:1,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"año",SIMPLE:1},
            texto: {TIPO:"TP_TEXT",EDITOR:1,VISIBILIDAD:"TP_VISIBLE",FIELDSET:1,NOMBRE:"texto"}
        },
        FORM: [
            {
                '<div class="col-12">/order/</div>': ['order']
            },
            {
                '<div class="col-12">/texto/</div>' : ['texto']
            }
        ],
        EDITOR: {
            texto: {
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
        }
    },

    number: {
        TABLE: "numbers",
        ROUTE: "numbers",
        ADD: 1,
        REFRESH: true,
        BTN: ['d', 'e'],
        ATRIBUTOS: {
            province: {TIPO:"TP_STRING",LABEL:1,RULE: "required|max:150",MAXLENGTH:150,NECESARIO:1,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"Provincia", DEFAULT: "CIUDAD DE BUENOS AIRES"},
            name: {TIPO:"TP_STRING",LABEL:1,RULE: "required|max:150",MAXLENGTH:150,NECESARIO:1,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"Nombre"},
            person: {TIPO:"TP_STRING",LABEL:1,RULE: "required|max:150",MAXLENGTH:150,NECESARIO:1,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"Responsable"},
            internal: {TIPO:"TP_STRING",LABEL:1,RULE: "max:5",MAXLENGTH:5,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"Interno"},
        },
        
        FORM: [
            {
                '<div class="col-12 col-md-5">/province/</div><div class="col-12 col-md-7">/name/</div>': ['province', 'name']
            },
            {
                '<div class="col-12 col-md-8">/person/</div><div class="col-12 col-md-4">/internal/</div>': ['internal', 'person']
            }
        ]
    },
    config: {
        TABLE: "configs",
        ROUTE: "configs",
        ADD: 1,
        BTN: ['d', 'e'],
        ATRIBUTOS: {
            name: {TIPO:"TP_STRING",LABEL:1,RULE: "required|max:30",MAXLENGTH:30,NECESARIO:1,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"nombre",TH:"200px"},
            value: {TIPO:"TP_TEXT",RULE: "required|max:255", MAXLENGTH:255,NECESARIO: 1,VISIBILIDAD:"TP_VISIBLE",LABEL:1,NOMBRE:"valor",NORMAL:1},
        },
        
        FORM: [
            {
                '<div class="col-12 col-md">/name/</div>': ['name']
            },
            {
                '<div class="col-12 col-md">/value/</div>': ['value']
            }
        ]
    },
    text: {
        TABLE: "texts",
        ROUTE: "texts",
        ADD: 1,
        BTN: ['d', 'e'],
        ATRIBUTOS: {
            name: {TIPO:"TP_STRING",LABEL:1,RULE: "required|max:30",MAXLENGTH:30,NECESARIO:1,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"nombre",TH:"200px"},
            value: {TIPO:"TP_TEXT",EDITOR:1,RULE: "required", NECESARIO: 1,VISIBILIDAD:"TP_VISIBLE",FIELDSET:1, LABEL:1,NOMBRE:"texto"},
        },
        
        FORM: [
            {
                '<div class="col-12 col-md">/name/</div>': ['name']
            },
            {
                '<div class="col-12 col-md">/value/</div>': ['value']
            }
        ],
        EDITOR: {
            value: {
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
                removeButtons: 'Save,NewPage,ExportPdf,Preview,Print,Templates,Cut,Copy,Paste,PasteText,PasteFromWord,Redo,Undo,Find,Replace,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Strike,Subscript,Superscript,RemoveFormat,CopyFormatting,Outdent,Indent,Blockquote,CreateDiv,BidiLtr,BidiRtl,Language,Anchor,Image,Flash,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe,Styles,Format,Font,Maximize,ShowBlocks,About',
                height: '300px'
            },
        }
    },

    family: {
        TABLE: "families",
        ROUTE: "families",
        ADD: 1,
        REFRESH: true,
        BTN: ['d', 'e'],
        ATRIBUTOS: {
            name: {TIPO:"TP_STRING",LABEL:1,RULE: "required|max:200",MAXLENGTH:200,NECESARIO:1,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"título"},
            name_slug: {TIPO:"TP_SLUG",VISIBILIDAD:"TP_INVISIBLE", COLUMN: "name"},
            icon: {TIPO:"TP_IMAGE", EXT: "jpeg, png, jpg, gif", FOLDER: "products/category",RULE: "nullable|mimes:jpeg,png,jpg,gif|max:2048", VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/*",NOMBRE:"Imagen",WIDTH:"auto", HEIGHT:"300px",TH:"300px"},
            color: {TIPO:"TP_COLOR",NECESARIO:1,VISIBILIDAD:"TP_VISIBLE"},
        },
        
        FORM: [
            {
                '<div class="col-12 col-md">/name/</div>': ['name']
            },
            {
                '<div class="col-12 col-md-6">/icon/</div><div class="col-12 col-md-6"><div class="row"><div class="col-12">/color/</div></div></div>': ['icon','color']
            }
        ]
    },

    /**********************************
            DESCARGAS
     ********************************** */
    download: {
        TABLE: "downloads",
        ROUTE: "downloads",
        ADD: 1,
        BTN: ['d', 'e'],
        ATRIBUTOS: {
            image: {TIPO:"TP_IMAGE", EXT: "jpeg, png, jpg, gif", FOLDER: "descargas",RULE: "nullable|mimes:jpeg,png,jpg,gif|max:2048", VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/*",NOMBRE:"Imagen",WIDTH:"auto", HEIGHT:"300px",TH:"300px"},
            type: {TIPO:"TP_ENUM", RULE: "required", NECESARIO: 1, LABEL: 1,ENUM:[{id: "PUBL", text : "Pública"}, { id: "CATA", text: "Catálogo (Privada)"}, {id: "PREC", text: "Listas de precios (Privada)"}, {id: "OTRA", text: "Otra"}],VISIBILIDAD:"TP_VISIBLE",NOMBRE:"tipo",NORMAL:1},
            name: {TIPO:"TP_TEXT", RULE: "required", NECESARIO: 1,LABEL:1,EDITOR:1,VISIBILIDAD:"TP_VISIBLE",FIELDSET:1,NOMBRE:"nombre"},
            parts: {TIPO:"TP_ARRAY",COLUMN:"files",VISIBILIDAD:"TP_VISIBLE_TABLE",NOMBRE:"partes",CLASS:"text-center"}
        },
        FORM: [
            {
                '<div class="col-12 col-md-6"><div class="row"><div class="col-12">/type/</div><div class="col-12 mt-3">/name/</div></div></div><div class="col-12 col-md-6">/image/</div>' : ['name', 'type', 'image'],
            },
        ],
        NECESARIO: {
            'type' : { CREATE : 1 , UPDATE : 1 },
        },
        EDITOR: {
            name: {
                toolbarGroups: [],
                height: "70px"
            }
        }
    },
    download_part: {
        ONE: 1,
        MULTIPLE: "Archivo",
        FUNCTION: "file",
        NOMBRE: "Archivos",
        COLUMN: "files",
        ATRIBUTOS: {
            order: {TIPO:"TP_ENTERO",LABEL:1,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"orden", SORTEABLE: 1, DEFAULT: "0"},
            file: {TIPO:"TP_FILE", EXT: "pdf, xls, txt, bdf", FOLDER: "descargas/partes",RULE: "nullable|mimes:pdf,xls,txt,bdf", VISIBILIDAD:"TP_VISIBLE",ACCEPT:"application/pdf, application/vnd.ms-excel, text/plain, .dbf,.DBF,.txt", NOMBRE:"Archivo"},
        },
        FORM: [
            {
                '<div class="col-12 col-md-6">/order/</div>':['order'],
            },
            {
                '<div class="col-12 col-md">/file/</div>':['file']
            }
        ]
    },


    /**********************************
            CALIDAD
     ********************************** */
    calidad: {
        ATRIBUTOS: {
            titulo: {TIPO:"TP_STRING",LABEL:1,MAXLENGTH:100,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"título"},
            subtitulo: {TIPO:"TP_STRING",LABEL:1,MAXLENGTH:100,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"subtítulo"},
            texto: {TIPO:"TP_TEXT",EDITOR:1,VISIBILIDAD:"TP_VISIBLE",FIELDSET:1, LABEL:1,NOMBRE:"texto"},
            frase: {TIPO:"TP_TEXT",EDITOR:1,VISIBILIDAD:"TP_VISIBLE",FIELDSET:1, LABEL: 1,NOMBRE:"frase"},
        },
        FORM: [
            {
                '<div class="col-12">/titulo/</div>' : ['titulo']
            },
            {
                '<div class="col-12">/subtitulo/</div>' : ['subtitulo']
            },
            {
                '<div class="col-12 col-md">/texto/</div><div class="col-12 col-md">/frase/</div>' : ['texto','frase']
            }
        ],
        EDITOR: {
            texto: {
                toolbarGroups: [
                    { name: "basicstyles", groups: ["basicstyles"] },
                    { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
                    { name: 'links' },
                    { name: 'colors', groups: [ 'TextColor', 'BGColor' ] },
                ],
                colorButton_colors : colorPick,
                removeButtons: 'CreateDiv,Language',
                height: '300px'
            },
            frase: {
                toolbarGroups: [
                    { name: "basicstyles", groups: ["basicstyles"] },
                    { name: 'colors', groups: [ 'TextColor', 'BGColor' ] },
                ],
                colorButton_colors : colorPick,
                removeButtons: 'CreateDiv,Language',
                height: '70px'
            }
        }
    },
    calidad_politica: {
        COLUMN: "politica",   
        ONE: 1,
        NOMBRE: "Política",
        ATRIBUTOS: {
            titulo: {TIPO:"TP_STRING",LABEL:1,MAXLENGTH:100,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"título"},
            texto: {TIPO:"TP_TEXT",EDITOR:1,VISIBILIDAD:"TP_VISIBLE",FIELDSET:1, LABEL: 1,NOMBRE:"texto"},
        },
        FORM: [
            {
                '<div class="col-12">/titulo/</div>' : ['titulo']
            },
            {
                '<div class="col-12 col-md">/texto/</div>' : ['texto']
            }
        ],
        EDITOR: {
            texto: {
                toolbarGroups: [
                    { name: "basicstyles", groups: ["basicstyles"] },
                    { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
                    { name: 'links' },
                    { name: 'colors', groups: [ 'TextColor', 'BGColor' ] },
                    { name: 'paragraph', groups: [ 'list', 'align' , 'indent' ] },
                ],
                colorButton_colors : colorPick,
                removeButtons: 'CreateDiv,Language',
                height: '300px'
            }
        }
    },
    calidad_garantia: {
        COLUMN: "garantia",   
        ONE: 1,
        NOMBRE: "Garantía",
        ATRIBUTOS: {
            titulo: {TIPO:"TP_STRING",LABEL:1,MAXLENGTH:100,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"título"},
            texto: {TIPO:"TP_TEXT",EDITOR:1,VISIBILIDAD:"TP_VISIBLE",FIELDSET:1,NOMBRE:"texto"},
        },
        FORM: [
            {
                '<div class="col-12">/titulo/</div>' : ['titulo']
            },
            {
                '<div class="col-12 col-md">/texto/</div>' : ['texto']
            }
        ],
        EDITOR: {
            texto: {
                toolbarGroups: [
                    { name: "basicstyles", groups: ["basicstyles"] },
                    { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
                    { name: 'links' },
                    { name: 'colors', groups: [ 'TextColor', 'BGColor' ] },
                    { name: 'paragraph', groups: [ 'list', 'align' , 'indent' ] },
                ],
                colorButton_colors : colorPick,
                removeButtons: 'CreateDiv,Language',
                height: '300px'
            }
        }
    },
};