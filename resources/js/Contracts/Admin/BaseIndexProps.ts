
export default {
    items: {
        type: Object,
        required: true,
    },
    counts: {
        type: Object,
        required: true,
    },
    tab: {
        type: String,
        default: null,
    },
    query: {
        type: String,
        default: null,
    },
    date: {
        type: [String, Array],
        default: null,
    },
    per_page: {
        type: Number,
        default: 10,
    },
    errors: {
        type: Object,
        default: {},
    },
};
