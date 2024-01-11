<template>
    <textarea
      :name="name"
      class="content-editor hidden"
      @change="handleChange($event, false)"
    >{{ value }}</textarea>
  <partial-input-error-message :error-message="errorMessage"/>
</template>

<script setup>
import '~/assets/js/tinymce/skins/skin.min.css';
import '~/assets/js/tinymce/tinymce.min.js';

import {onMounted, onUnmounted, watch} from "vue";
import {useRouter} from "vue-router";
import {useField} from "vee-validate";
import PartialInputErrorMessage from "../partials/PartialInputErrorMessage.vue";

const props = defineProps({
  readonly: {
    type: Boolean,
    default: false,
  },
  value: String,
  name: {
    type: String,
    required: true,
  },
})

const router = useRouter()

const {value, errorMessage, handleChange} = useField(() => props.name, undefined)

if (props.value && props.value.length)
  value.value = props.value

watch(() => props.value, () => {
  value.value = props.value
})

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

onUnmounted(() => {
  tinymce.remove(".content-editor");
})
</script>
