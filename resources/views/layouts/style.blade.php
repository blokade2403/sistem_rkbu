<style>
    .stepper {
        position: relative;
        margin: 10px;
        padding-left: 10px;

        /* jarak kiri untuk waktu */
    }

    /* Garis Vertikal di Tengah Bulat */
    .stepper::before {
        content: "";
        position: absolute;
        top: 0;
        bottom: 0;
        left: 10px;
        /* 50px waktu + 10px margin + 15px (tengah lingkaran 30px) */
        width: 2px;
        background: #ddd;
        z-index: 0;

    }

    /* Step Container */
    .step {
        position: relative;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        z-index: 1;

    }

    /* Waktu */
    .time {
        width: 100px;
        text-align: right;
        font-size: 10px;
        color: #777;
        margin-right: 10px;
    }

    .time.highlight {
        color: red;
        font-weight: bold;
    }

    .time.warning {
        color: rgb(255, 132, 0);
        font-weight: bold;
    }

    /* Lingkaran */
    .circle {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: 2px solid #bbb;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        z-index: 2;
        /* Supaya di depan garis */
    }

    /* Status */
    .step.completed .circle {
        background: #ffffff;
        border-color: #1ABC9C;

    }

    .step.active .circle {
        border-color: #f1870f;
        background: #fff;
    }

    .step.stop .circle {
        border-color: #f70606;
        background: #fff;
    }

    /* Lingkaran */
    .circle2 {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 2px solid #bbb;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        z-index: 2;
        /* Supaya di depan garis */
    }

    /* Status */
    .step.completed .circle2 {
        background: #ffffff;
        border-color: #1ABC9C;

    }

    .step.active .circle2 {
        border-color: #f1870f;
        background: #fff;
    }

    .step.stop .circle2 {
        border-color: #f70606;
        background: #fff;
    }

    .content h6 {
        margin: 0;
        font-weight: 600;
    }

    .content p {
        margin: 0;
        font-size: 13px;
        color: #777;
    }

    .custom-modal {
        max-width: 70% !important;
        /* hampir full screen */
    }
</style>
