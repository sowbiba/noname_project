module.exports = function(grunt) {
    require('load-grunt-tasks')(grunt);

    grunt.initConfig({
        less: {
            libs: {
                options: {
                    compress: false,
                    ieCompat: true,
                    noLineComments: true,
                    paths: ['assets/css']
                },
                files: {
                    '.tmp/css/fontawesome.css': [
                        'app/Resources/lib/fontawesome/less/font-awesome.less'
                    ],
                    '.tmp/css/bootstrap.css': [
                        'app/Resources/lib/bootstrap/less/bootstrap.less'
                    ],
                    '.tmp/css/bootstrap-datepicker.css': [
                        'app/Resources/lib/bootstrap-datepicker/build/build_standalone3.less'
                    ],
                    '.tmp/css/bootstrap-switch.css': [
                        'app/Resources/lib/bootstrap-switch/src/less/bootstrap3/build.less'
                    ],
                    '.tmp/css/bootstrap-multiselect.css': [
                        'app/Resources/lib/bootstrap-multiselect/dist/less/bootstrap-multiselect.less'
                    ],
                    '.tmp/css/vendor_front.css': [
                        'app/Resources/lib/bootstrap/less/bootstrap.less'
                    ],
                    '.tmp/css/back.css': [
                        'src/AppBundle/Resources/less/back.less'
                    ]
                }
            }
        },
        cssmin: {
            combine: {
                options:{
                    report: 'gzip',
                    keepSpecialComments: 0
                },
                files: {
                    'web/css/errors.min.css': [
                        '.tmp/css/errors.css'
                    ],
                    'web/css/bootstrap.min.css': [
                        '.tmp/css/bootstrap.css',
                        '.tmp/css/bootstrap-datepicker.css',
                        '.tmp/css/bootstrap-switch.css',
                        '.tmp/css/bootstrap-multiselect.css'
                    ],
                    'web/css/bootstrap-datepicker.css': [
                        '.tmp/css/bootstrap-datepicker.css'
                    ],
                    'web/css/bootstrap-switch.css': [
                        '.tmp/css/bootstrap-switch.css'
                    ],
                    'web/css/bootstrap-multiselect.css': [
                        '.tmp/css/bootstrap-multiselect.css'
                    ],
                    'web/css/bootstrap_back.css': [
                        '.tmp/css/bootstrap_back.css'
                    ],
                    'web/css/fontawesome.min.css': [
                        '.tmp/css/fontawesome.css'
                    ],
                    'web/css/gridster.min.css': [
                        'app/Resources/lib/gridster/dist/jquery.gridster.min.css'
                    ],
                    'web/css/x-editable.css': [
                        'app/Resources/lib/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css'
                    ],
                    'web/css/jsonview.css': [
                        'app/Resources/lib/jquery-jsonview/dist/jquery.jsonview.css'
                    ],
                    'web/css/main_back.css': [
                        '.tmp/css/bootstrap_back.css',
                        '.tmp/css/fontawesome.css'
                    ],
                    'web/css/back/layout.css': [
                        '.tmp/css/back.css'
                    ],
                    'web/css/main.css': [
                        '.tmp/css/fontawesome.css',
                        '.tmp/css/bootstrap.css'
                    ],
                    'web/css/nanoscroller.css': [
                        'app/Resources/lib/nanoscroller/bin/css/nanoscroller.css'
                    ],
                    'web/css/layout_front.css': [
                        '.tmp/css/layout_front.css'
                    ],
                    'web/css/vendor_front.css': [
                        '.tmp/css/vendor_front.css'
                    ]
                }
            }
        },
        typescript: {
            base: {
                src: ['app/Resources/ts/**/*.ts'],
                dest: '.tmp/js/ts/',
                options: {
                    target: 'es5',
                    module: 'amd',
                    sourceMap: true,
                    declaration: true
                }
            }
        },
        concat: {
            options: {
                separator: ';'
            },
            dist: {
                files: {
                    'web/js/bootstrap.js': [
                        'app/Resources/lib/bootstrap/js/transition.js',
                        'app/Resources/lib/bootstrap/js/alert.js',
                        'app/Resources/lib/bootstrap/js/button.js',
                        'app/Resources/lib/bootstrap/js/carousel.js',
                        'app/Resources/lib/bootstrap/js/collapse.js',
                        'app/Resources/lib/bootstrap/js/dropdown.js',
                        'app/Resources/lib/bootstrap/js/modal.js',
                        'app/Resources/lib/bootstrap/js/tooltip.js',
                        'app/Resources/lib/bootstrap/js/popover.js',
                        'app/Resources/lib/bootstrap/js/scroolspy.js',
                        'app/Resources/lib/bootstrap/js/tab.js',
                        'app/Resources/lib/bootstrap/js/affix.js'
                    ],
                    'web/js/jquery.js': [
                        'app/Resources/lib/jquery/dist/jquery.js'
                    ],
                    'web/js/jquery-ui/draggable.js': [
                        'app/Resources/lib/jquery-ui/ui/core.js',
                        'app/Resources/lib/jquery-ui/ui/widget.js',
                        'app/Resources/lib/jquery-ui/ui/mouse.js',
                        'app/Resources/lib/jquery-ui/ui/draggable.js'
                    ],
                    'web/js/bootstrap-datepicker.js': [
                        'app/Resources/lib/bootstrap-datepicker/js/bootstrap-datepicker.js',
                        'app/Resources/lib/bootstrap-datepicker/js/locales/bootstrap-datepicker.fr.js'
                    ],
                    'web/js/bootstrap-switch.js': [
                        'app/Resources/lib/bootstrap-switch/dist/js/bootstrap-switch.js'
                    ],
                    'web/js/bootstrap-multiselect.js': [
                        'app/Resources/lib/bootstrap-multiselect/dist/js/bootstrap-multiselect.js'
                    ],
                    'web/js/jquery.fileDownload.js': [
                        'app/Resources/lib/fileDownload/src/Scripts/jquery.fileDownload.js'
                    ],
                    'web/js/jquery.checkboxes.js': [
                        'app/Resources/lib/checkboxes.js/src/jquery.checkboxes.js'
                    ],
                    'web/js/jquery.sortable.js': [
                        'app/Resources/lib/sortable/Sortable.js'
                    ],
                    'web/js/latinize.js': [
                        'app/Resources/lib/latinize/latinize.js'
                    ],
                    'web/js/egg.js': [
                        'app/Resources/lib/egg/egg.js'
                    ],
                    'web/js/readmore.min.js': [
                        'app/Resources/lib/readmore/readmore.min.js'
                    ],
                    'web/js/gridster.min.js': [
                        'app/Resources/lib/gridster/dist/jquery.gridster.min.js'
                    ],
                    'web/js/x-editable.min.js': [
                        'app/Resources/lib/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.min.js'
                    ],
                    'web/js/dotdotdot.min.js': [
                        'app/Resources/lib/jQuery.dotdotdot/src/js/jquery.dotdotdot.min.js'
                    ],
                    'web/js/nanoscroller.min.js': [
                        'app/Resources/lib/nanoscroller/bin/javascripts/jquery.nanoscroller.min.js'
                    ],
                    'web/js/jsonview.min.js': [
                        'app/Resources/lib/jquery-jsonview/dist/jquery.jsonview.js'
                    ],
                    'web/js/tableHeadFixer.js': [
                        'app/Resources/lib/tableHeadFixer/tableHeadFixer.js'
                    ],
                    'web/js/respond.js': [
                        'app/Resources/lib/respond/dest/respond.min.js'
                    ],
                    'web/js/fosjsrouting.js': [
                        'vendor/friendsofsymfony/jsrouting-bundle/Resources/js/router.js'
                    ],
                    'web/js/translator.js': [
                        'vendor/willdurand/js-translation-bundle/Bazinga/Bundle/JsTranslationBundle/Resources/public/js/translator.min.js'
                    ],
                    'web/js/translations.js': [
                        'web/js/translations/*/*.js'
                    ]
                }
            }
        },
        uglify: {
            options: {
                mangle: false,
                sourceMap: true,
                sourceMapName: 'web/js/app.map'
            },
            dist: {
                files: {
                    'web/js/main.js': [
                        'src/AppBundle/Resources/public/javascripts/layout.js',
                        'src/AppBundle/Resources/public/javascripts/components/tooltips.js'
                    ]
                }
            }
        },
        copy: {
            dist: {
                files: [
                    {
                        expand: true,
                        cwd: 'app/Resources/lib/bootstrap/fonts/',
                        dest: 'web/fonts/',
                        src: ['**']
                    },
                    {
                        expand: true,
                        cwd: 'app/Resources/lib/fontawesome/fonts/',
                        dest: 'web/fonts/',
                        src: ['**']
                    },
                    {
                        expand: true,
                        cwd: 'app/Resources/lib/x-editable/dist/bootstrap3-editable/img',
                        dest: 'web/img',
                        src: ['**']
                    }
                ]
            }
        },
        shell: {
            bazingaJsTranslation: {
                command: [
                    'php app/console bazinga:js-translation:dump',
                    'php app/console assets:install',
                    'php app/console assets:install -e=prod'
                ].join(';')
            },
            assets: {
                command: [
                    'php app/console assets:install',
                    'php app/console assets:install -e=prod'
                ].join(';')
            }
        },
        watch: {
            css: {
                files: [
                    'src/**/Resources/less/**/*.less'
                ],
                tasks: ['css']
            },
            javascript: {
                files: [
                    'src/**/Resources/public/javascripts/*.js',
                    'src/**/Resources/public/javascripts/**/*.js',
                    'app/Resources/ts/**/*.ts'
                ],
                tasks: ['javascript']
            }
        }
    });

    grunt.registerTask('default', ['css', 'javascript', 'copy', 'shell' ]);
    grunt.registerTask('javascript', ['typescript', 'uglify', 'concat']);
    grunt.registerTask('css', ['less','cssmin']);
    grunt.registerTask('cp', ['copy']);
    grunt.registerTask('sh', ['shell'])
};