<template>
  <partial-input-error-message :error-message="errorMessage"/>
  <textarea
    ref="inp"
    :name="name"
    class="content-editor hidden"
    @blur="checkInput($event)"
    @change="handleEditorChange($event)"
    @input="checkInput($event)"
    @keydown="checkInput($event)"
    @keyup="checkInput($event)"
  >{{ value }}</textarea>
</template>

<script setup>
import {onMounted, onUnmounted, ref, watch} from "vue";
import {useRouter} from "vue-router";
import {useField} from "vee-validate";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";

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
  type: {
    type: String,
    validator: (value) => {
      return ['advanced', 'basic'].indexOf(value) !== -1
    },
    default: 'advanced',
  },
})
const emit = defineEmits([
  'input', 'blur', 'keydown', 'keyup', 'mount',
])

const router = useRouter()
const inp = ref()

const {value, errorMessage, handleChange} = useField(() => props.name)

if (props.value && props.value.length) {
  value.value = props.value
}

watch(() => props.value, () => {
  value.value = props.value
})

//-----------------------------------------------------
function handleEditorChange(event) {
  handleChange(event, false)
}

//-----------------------------------------------------
// configuration setup
//-----------------------------------------------------
// if it's "basic"
let config = {
  plugins: [
    'textcolor',
    'emoticons paste textpattern',
    'advlist autolink lists link image preview hr anchor',
    'searchreplace wordcount fullscreen',
    'insertdatetime nonbreaking table contextmenu'
  ],
  toolbar1: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
  toolbar2: "forecolor backcolor",
  font_formats: 'Iran yekan=IRANYekanWeb Regular,Arial,sans-serif;Iran yekan bold=IRANYekanWeb Bold,Arial,sans-serif;Iran yekan light=IRANYekanWeb Light,Arial,sans-serif;Sans Serif=sans-serif;Arial=arial,helvetica,sans-serif;Courier New=courier new,courier,monospace;Georgia=georgia,palatino;Tahoma=tahoma,arial,helvetica,sans-serif;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;',
  fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
  content_css: '/tinymce/fonts.css',
  content_style: "body { font-family: 'IRANYekanWeb Regular', Arial, sans-serif; }",
}

if (props.type === 'advanced') {
  config.plugins = [
    'textcolor',
    'emoticons template paste textpattern imagetools',
    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
    'searchreplace wordcount visualblocks visualchars code fullscreen',
    'insertdatetime media nonbreaking save table contextmenu directionality'
  ]
  config.toolbar1 = "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat"
  config.toolbar2 = "fontselect fontsizeselect | forecolor backcolor"
  config.image_advtab = true
  config.file_picker_callback = function (callback, value, meta) {
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
  }

  // config.templates: [
  //     {title: 'Test template 1', content: 'Test 1'},
  // ],
}

//-----------------------------------------------------
const dynamicLink = ref(null)
const dynamicScript = ref(null)

onMounted(() => {
  // Dynamically import your CSS file
  const link = document.createElement('link')
  link.href = '/tinymce/skins/skin.min.css'
  link.rel = 'stylesheet'
  document.head.appendChild(link)

  // Dynamically import your JS file
  const script = document.createElement('script')
  script.src = '/tinymce/tinymce.min.js'
  script.async = true

  // Add an onload event to initialize TinyMCE after the script is loaded
  script.onload = () => {
    tinymce.init(Object.assign({
      setup: function (editor) {
        editor.on('change', function () {
          value.value = editor.getContent()
        });
      },
      // for custom css of the actual page
      // content_css: '',
      selector: '.content-editor',
      height: 500,
      theme: 'modern',
      readonly: props.readonly,
    }, config));

    emit('mount', {input: inp.value})
  };

  document.body.appendChild(script)

  // Save the dynamically created elements to access them during unmount
  dynamicLink.value = link
  dynamicScript.value = script
})

onUnmounted(() => {
  tinymce.remove(".content-editor")

  // Remove the dynamically created elements during unmount
  if (dynamicLink.value && dynamicLink.value.parentNode) {
    dynamicLink.value.parentNode.removeChild(dynamicLink.value)
  }

  if (dynamicScript.value && dynamicScript.value.parentNode) {
    dynamicScript.value.parentNode.removeChild(dynamicScript.value)
  }
})

function checkInput(event) {
  emit(event.type, event.target.value || '')
}

defineExpose({
  input: inp,
})
</script>

<style>
/* Ensure the editor content uses the custom font */
.mce-content-body {
  font-family: "IRANYekanWeb Regular", Arial, sans-serif;
  font-size: 16px;
}
</style>
