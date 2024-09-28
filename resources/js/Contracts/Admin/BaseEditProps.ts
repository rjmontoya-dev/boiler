 export default {
    item: {
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
    logs: {
        type: Object,
        default: {},
    },
    date: {
        type: [String, Array],
        default: null,
    },
    per_page: {
        type: Number,
        default: 10,
    },
    logsCount: {
        type: Number,
        default: 0,
    },
    filterLogQuery: {
        type: String,
        default: null,
    },
    filterLogEvent: {
        type: String,
        default: null,
    },
    filterLogDate: {
        type: String,
        default: null,
    },
    queryFilters: {
        type: Object,
        default: {},
    },
    errors: {
        type: Object,
        default: {},
    },
};
