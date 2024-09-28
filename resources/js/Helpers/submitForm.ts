import { Form } from "../Interface/Data/Form";
import { useForm } from "@inertiajs/vue3";
import { VisitOptions } from "@inertiajs/core";
import { watch } from "vue";

export default function PRXForm(
    formData: Form,
    submitUrl: string,
    option?: Partial<VisitOptions>
) {
/*--------------*
    * FORM
    *--------------*/
const form = useForm(formData);

/*--------------*
    * WATCHERS
    *--------------*/
watch(
    () => Object.keys(formData).map((key) => form[key]),
    (val, oldVal) => {
        for (let i = 0; i < val.length; i++) {
            if (val[i] !== oldVal[i]) {
                form.errors[Object.keys(formData)[i]] = null;
            }
        }
    },
    { deep: true }
);

/*--------------*
* METHODS
*--------------*/
// method: string = 'post' //param remove because it has error
  const submit = () => {
    form.submit('post', submitUrl, option);
};
    return {
        form,
        submit,
    };
}
