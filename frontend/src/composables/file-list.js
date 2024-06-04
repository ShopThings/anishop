import {ref} from 'vue'
import mime from 'mime-types'

export const Storages = {
  public: 'public',
  local: 'private',
}

export const FileSizes = {
  ORIGINAL: 'original',
  SMALL: 'small',
  MEDIUM: 'medium',
  LARGE: 'large',
}

export const UploadTypes = {
  NOT_SET: null,
  UPLOADING: 'uploading',
  SUCCESS: true,
  FAILED: false,
}

export function useFileList() {
  const files = ref([])
  const allowedExt = [
    'jpeg', 'png', 'jpg', 'gif', 'svg', 'csv', 'txt', 'xlx', 'xlsx',
    'xls', 'pdf', 'docx', 'doc', 'mp4', 'mp3',
  ]

  function addFiles(newFiles) {
    let newUploadableFiles = [...newFiles]
      .map((file) => new UploadableFile(file))
      .filter((file) => {
        return !fileExists(file.id) && isAllowedExt(file)
      })
    files.value = files.value.concat(newUploadableFiles)
  }

  function fileExists(otherId) {
    return files.value.some(({id}) => id === otherId)
  }

  function isAllowedExt(file) {
    return allowedExt.indexOf(file.extension) !== -1
  }

  function removeFile(file) {
    const index = files.value.indexOf(file)

    if (index > -1) files.value.splice(index, 1)
  }

  function isImageExt(ext) {
    if (!ext) return false

    return ['jpeg', 'png', 'jpg', 'gif', 'svg'].indexOf(ext.toLowerCase()) !== -1
  }

  function isSizedImageExt(ext) {
    if (!ext) return false

    return ['jpeg', 'png', 'jpg'].indexOf(ext.toLowerCase()) !== -1
  }

  function isAudioExt(ext) {
    if (!ext) return false

    return ['mp3'].indexOf(ext.toLowerCase()) !== -1
  }

  function isVideoExt(ext) {
    if (!ext) return false

    return ['mp4'].indexOf(ext.toLowerCase()) !== -1
  }

  return {files, addFiles, removeFile, isImageExt, isSizedImageExt, isAudioExt, isVideoExt}
}

class UploadableFile {
  constructor(file) {
    this.file = file
    this.id = `${file.name}-${file.size}-${file.lastModified}-${file.type}`
    this.url = URL.createObjectURL(file)
    this.extension = mime.extension(file.type)
    this.progress = 0
    this.abort = null
    this.errorMessage = null
    this.status = null
  }
}
