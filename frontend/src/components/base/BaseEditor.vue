<template>
    <textarea
        class="content-editor"
    ></textarea>
</template>

<script setup>
import {onMounted} from "vue";
import {useRouter} from "vue-router";

const props = defineProps({
    readonly: {
        type: Boolean,
        default: false,
    },
})

const router = useRouter()

onMounted(() => {
    tinymce.init({
        selector: '.content-editor',
        height: 500,
        theme: 'modern',
        readonly: props.readonly,
        plugins: [
            'textcolor',
            'emoticons template paste textpattern imagetools',
            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality'
        ],
        toolbar1: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | forecolor backcolor",
        image_advtab: true,
        // templates: [
        //     {title: 'Test template 1', content: 'Test 1'},
        // ],
        // content_css: [],
        file_picker_callback: function (callback, value, meta) {
            let cmsURL = router.resolve({name: 'admin.file_manager.editor'}).fullPath // script URL - use an absolute path!
            cmsURL = cmsURL + "?type=" + meta.filetype;

            tinymce.activeEditor.windowManager.open({
                title: 'مدیریت فایل‌ها',
                url: cmsURL,
                width: 600,
                height: 600
            }, {
                onInsert: function (url) {
                    callback(url);
                }
            });
        },
    });
})
</script>

<style scoped>

</style>
