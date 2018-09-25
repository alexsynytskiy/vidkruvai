/**
 * Simple realization of Observer pattern
 *
 * @type {{EVENT_ON_GRID_RELOAD: string, listeners: {}, subscribe: ObserverList.subscribe, notify: ObserverList.notify}}
 */
var ObserverList = {
    EVENT_ON_GRID_RELOAD: 'grid-reload',

    EVENT_ON_NOTIFICATION_STATUS_CHANGE: 'notification-status-change',
    EVENT_ON_NOTIFICATION_BEFORE_STATUS_CHANGE: 'notification-before-status-change',
    EVENT_ON_NOTIFICATION_AFTER_STATUS_CHANGE: 'notification-after-status-change',
    EVENT_ON_NOTIFICATION_FILTER_FIRE: 'notification-filter-fire',


    EVENT_ON_NEWS_STATUS_CHANGE: 'news-status-change',
    EVENT_ON_NEWS_BEFORE_STATUS_CHANGE: 'news-before-status-change',
    EVENT_ON_NEWS_AFTER_STATUS_CHANGE: 'news-after-status-change',
    EVENT_ON_NEWS_FILTER_FIRE: 'news-filter-fire',

    listeners: {},

    /**
     *
     * @param eventName Name of the event
     * @param listener Function that will be attached to the event, and will be executed during event-firing, to single event may be attached multiple listeners
     * @param listenerArgs Arguments that will be passed to the listener during event-firing
     */
    subscribe: function (eventName, listener, listenerArgs) {
        listenerArgs = (typeof listenerArgs !== 'undefined' ? listenerArgs : []);

        if (!this.listeners[eventName]) {
            this.listeners[eventName] = [];
        }

        this.listeners[eventName].push([listener, listenerArgs]);
    },
    notify: function (eventName) {
        if (this.listeners[eventName]) {
            for (var i = 0; i < this.listeners[eventName].length; i++) {
                var func = this.listeners[eventName][i][0],
                    funcArgs = this.listeners[eventName][i][1];

                func.apply(this, funcArgs);
            }
        }
    }
};
