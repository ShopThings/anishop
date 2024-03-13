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
let config = {}
if (props.type === 'advanced') {
  config = {
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
  }
} else { // if it's "basic"
  config = {
    plugins: [
      'textcolor',
      'emoticons paste textpattern',
      'advlist autolink lists link image preview hr anchor',
      'searchreplace wordcount fullscreen',
      'insertdatetime nonbreaking table contextmenu'
    ],
    toolbar1: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | forecolor backcolor",
  }
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
