/**
 * EventsModel
 * Holds pagination state and data indices.
 */
class EventsModel {
    constructor(items, pageSize = 7) {
        this.items = items;
        this.pageSize = pageSize;
        this.currentPage = 1;
    }

    totalPages() {
        return Math.max(1, Math.ceil(this.items.length / this.pageSize));
    }

    pageIndices() {
        const start = (this.currentPage - 1) * this.pageSize;
        return { start, end: start + this.pageSize };
    }

    next() {
        if (this.currentPage < this.totalPages()) {
            this.currentPage++;
        }
    }

    prev() {
        if (this.currentPage > 1) {
            this.currentPage--;
        }
    }
}

/**
 * EventsView
 * Responsible for DOM updates only.
 */
class EventsView {
    constructor(root) {
        this.items = root.querySelectorAll("[data-event-item]");
        this.status = root.querySelector("[data-status]");
        this.pager = root.querySelector("[data-pager]");
    }

    render(model) {
        const { start, end } = model.pageIndices();

        this.items.forEach((el, index) => {
            el.style.display = (index >= start && index < end) ? "block" : "none";
        });

        this.status.textContent =
            `Page ${model.currentPage} of ${model.totalPages()}`;

        this.pager.style.display =
            model.totalPages() > 1 ? "flex" : "none";
    }
}

/**
 * EventsPresenter
 * Connects model and view.
 */
class EventsPresenter {
    constructor(model, view) {
        this.model = model;
        this.view = view;
        this.view.render(this.model);
    }
}

// Bootstrapping
document.addEventListener("DOMContentLoaded", () => {
    const root = document.querySelector("[data-events-page]");
    if (!root) return;

    const items = root.querySelectorAll("[data-event-item]");
    if (items.length === 0) return;

    const model = new EventsModel(items, 7);
    const view = new EventsView(root);
    new EventsPresenter(model, view);

    // simple controls (keyboard / future buttons)
    document.addEventListener("keydown", (e) => {
        if (e.key === "ArrowRight") {
            model.next();
            view.render(model);
        }
        if (e.key === "ArrowLeft") {
            model.prev();
            view.render(model);
        }
    });
});
