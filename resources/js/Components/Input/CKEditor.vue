<template>
    <div>
      <textarea ref="editor"></textarea>
    </div>
  </template>

  <script>
  import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

  export default {
    name: 'CKEditor',
    props: {
      modelValue: {
        type: String,
        default: ''
      }
    },
    mounted() {
      // Initialize CKEditor
      ClassicEditor.create(this.$refs.editor)
        .then(editor => {
          this.editorInstance = editor;
          // Set the initial editor content
          editor.setData(this.modelValue);

          // Listen for editor changes and emit the updated content
          editor.model.document.on('change:data', () => {
            const editorData = editor.getData();
            this.$emit('update:modelValue', editorData);
          });
        })
        .catch(error => {
          console.error('There was a problem initializing the editor:', error);
        });
    },
    beforeUnmount() {
      // Destroy the editor when the component is destroyed to free up resources
      if (this.editorInstance) {
        this.editorInstance.destroy();
      }
    }
  };
  </script>

  <style>
  /* Add any necessary styles for the editor */
  .ck-editor__editable {
    min-height: 200px;
  }
  </style>
