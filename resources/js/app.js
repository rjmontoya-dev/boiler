import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import CKEditor from './Components/Input/CKEditor.vue';

/*--------------*
 * CONTAINER
 *--------------*/
import DataTable from './Components/DataTable.vue';
import TableContainer from './Components/Containers/TableContainer.vue';

/*--------------*
 * ACTIVITY LOGS
 *--------------*/
import ActivityLogs from './Components/ActivityLogs.vue';

/*--------------*
 * FLASH MESSAGES
 *--------------*/
import FlashMessages from './Components/Modals/FlashMessages.vue';

/*--------------*
 * ICONS
 *--------------*/
import { PencilSquareIcon, TrashIcon, ArrowPathIcon } from '@heroicons/vue/24/outline'

/*--------------*
 * LAYOUTS
 *--------------*/
import AppLayout from './Layouts/AppLayout.vue';
import NavTabs from './Components/NavTabs.vue';

/*--------------*
 * ACTION BUTTONS
 *--------------*/
import ActionButton from './Components/ActionButtons/ActionButton.vue';
import CancelButton from './Components/ActionButtons/CancelButton.vue';
import CreateButton from './Components/ActionButtons/CreateButton.vue';
import DeleteButton from './Components/ActionButtons/DeleteButton.vue';
import EditButton from './Components/ActionButtons/EditButton.vue';
import RestoreButton from './Components/ActionButtons/RestoreButton.vue';

/*--------------*
 * INPUTS
 *--------------*/
import Dropzone from './Components/Input/Dropzone.vue';
import FilePreview from './Components/Input/FilePreview.vue';


/*--------------*
 * PARTIALS
 *--------------*/
import FormBuilder from './Components/Partials/FormBuilder.vue';
import SectionTitle from './Components/Partials/SectionTitle.vue';
import Paginator from './Components/Partials/Paginator.vue';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(CKEditor)
            .use(ZiggyVue)
            .mixin({
                methods: { route },
                components: {
                    //INPUTS
                    Dropzone,
                    FilePreview,

                    // ACTION BUTTONS
                    ActionButton,
                    CancelButton,
                    CreateButton,
                    DeleteButton,
                    EditButton,
                    RestoreButton,

                    //PARTIALS
                    FormBuilder,
                    SectionTitle,
                    Paginator,

                    //LAYOUTS
                    AppLayout,
                    NavTabs,

                    //ICONS
                    PencilSquareIcon,
                    TrashIcon,
                    ArrowPathIcon,

                    //FLASH MESSAGES
                    FlashMessages,

                    //CONTAINER
                    DataTable,
                    TableContainer,

                    //ACTIVITY LOGS
                    ActivityLogs,

                    CKEditor
                }
            })
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
