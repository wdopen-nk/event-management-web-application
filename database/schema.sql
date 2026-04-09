-- =========================
-- USERS
-- =========================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(64) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- EVENTS
-- =========================
CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(64) NOT NULL,
    description TEXT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    created_by INT NOT NULL,
    hero_image VARCHAR(255) DEFAULT NULL,

    CONSTRAINT fk_events_user
        FOREIGN KEY (created_by)
        REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- WORKSHOPS
-- =========================
CREATE TABLE workshops (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    name VARCHAR(128) NOT NULL,

    CONSTRAINT fk_workshops_event
        FOREIGN KEY (event_id)
        REFERENCES events(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- REGISTRATIONS
-- =========================
CREATE TABLE registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    registered_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT uq_user_event UNIQUE (user_id, event_id),

    CONSTRAINT fk_reg_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_reg_event
        FOREIGN KEY (event_id)
        REFERENCES events(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- REGISTRATION ↔ WORKSHOPS
-- =========================
CREATE TABLE registration_workshops (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registration_id INT NOT NULL,
    workshop_id INT NOT NULL,

    PRIMARY KEY (registration_id, workshop_id),

    CONSTRAINT fk_rw_registration
        FOREIGN KEY (registration_id)
        REFERENCES registrations(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_rw_workshop
        FOREIGN KEY (workshop_id)
        REFERENCES workshops(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;