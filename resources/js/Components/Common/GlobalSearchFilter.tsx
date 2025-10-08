import React, { useState } from 'react';
import {
    Col,
    Row,
    Button,
    Dropdown,
} from "react-bootstrap";
import Flatpickr from "react-flatpickr";
import Select from "react-select";
import { Link } from '@inertiajs/react';

const ProductsGlobalFilter = () => {
    return (
        <React.Fragment>
            <div className="col-sm-auto ms-auto">
                <div>
                    <Link
                        href="/apps-ecommerce-add-product"
                        className="btn btn-success"
                    >
                        <i className="ri-add-line align-bottom me-1"></i> Add
                        Product
                    </Link>
                </div>
            </div>
        </React.Fragment>
    );
};
const CustomersGlobalFilter = () => {
    const [customerStatus, setcustomerStatus] = useState(null);

    function handlecustomerStatus(customerStatus: any) {
        setcustomerStatus(customerStatus);
    }

    const customerstatus = [
        {
            options: [
                { label: "Status", value: "Status" },
                { label: "All", value: "All" },
                { label: "Active", value: "Active" },
                { label: "Block", value: "Block" },
            ],
        },
    ];
    return (
        <React.Fragment>
            <Col xl={7}>
                <Row className="g-3">
                    <Col sm={4}>
                        <div className="">
                            <Flatpickr
                                className="form-control"
                                id="datepicker-publish-input"
                                placeholder="Select a date"
                                options={{
                                    altInput: true,
                                    altFormat: "F j, Y",
                                    mode: "multiple",
                                    dateFormat: "d.m.y",
                                }}
                            />
                        </div>
                    </Col>

                    <Col sm={4}>
                        <div>
                            <Select
                                value={customerStatus}
                                onChange={(e: any) => {
                                    handlecustomerStatus(e);
                                }}
                                options={customerstatus}
                                name="choices-single-default"
                                id="idStatus"
                            ></Select>
                        </div>
                    </Col>

                    <Col sm={4}>
                        <div>
                            <button
                                type="button"
                                className="btn btn-primary w-100"
                            >
                                {" "}
                                <i className="ri-equalizer-fill me-2 align-bottom"></i>
                                Filters
                            </button>
                        </div>
                    </Col>
                </Row>
            </Col>
        </React.Fragment>
    );
};

const OrderGlobalFilter = () => {
    const [orderStatus, setorderStatus] = useState<any>([]);
    const [orderPayement, setorderPayement] = useState<any>(null);

    function handleorderStatus(orderstatus: any) {
        setorderStatus(orderstatus);
    }

    function handleorderPayement(orderPayement: any) {
        setorderPayement(orderPayement);
    }

    const orderstatus: any = [
        {
            options: [
                { label: "Status", value: "Status" },
                { label: "All", value: "All" },
                { label: "Pending", value: "Pending" },
                { label: "Inprogress", value: "Inprogress" },
                { label: "Cancelled", value: "Cancelled" },
                { label: "Pickups", value: "Pickups" },
                { label: "Returns", value: "Returns" },
                { label: "Delivered", value: "Delivered" },
            ],
        },
    ];

    const orderpayement = [
        {
            options: [
                { label: "Select Payment", value: "Select Payment" },
                { label: "All", value: "All" },
                { label: "Mastercard", value: "Mastercard" },
                { label: "Paypal", value: "Paypal" },
                { label: "Visa", value: "Visa" },
                { label: "COD", value: "COD" },
            ],
        },
    ];
    return (
        <React.Fragment>
            <Col sm={6} className="col-xxl-2">
                <div>
                    <Flatpickr
                        className="form-control"
                        id="datepicker-publish-input"
                        placeholder="Select a date"
                        options={{
                            altInput: true,
                            altFormat: "F j, Y",
                            mode: "multiple",
                            dateFormat: "d.m.y",
                        }}
                    />
                </div>
            </Col>

            <Col sm={4} className="col-xxl-2">
                <div>
                    <Select
                        value={orderStatus}
                        onChange={handleorderStatus}
                        options={orderstatus}
                        name="choices-single-default"
                        id="idStatus"
                    ></Select>
                </div>
            </Col>

            <Col sm={4} className="col-xxl-2">
                <div>
                    <Select
                        value={orderPayement}
                        onChange={handleorderPayement}
                        options={orderpayement}
                        name="choices-payment-default"
                        id="idPayment"
                    ></Select>
                </div>
            </Col>

            <Col sm={4} className="col-xxl-1">
                <div>
                    <button type="button" className="btn btn-primary w-100">
                        {" "}
                        <i className="ri-equalizer-fill me-1 align-bottom"></i>
                        Filters
                    </button>
                </div>
            </Col>
        </React.Fragment>
    );
};

const ContactsGlobalFilter = () => {
    const [sortBy, setSortBy] = useState<any>(null);

    function handleSortBy(selectedOption: any) {
        setSortBy(selectedOption);
    }

    const sortOptions = [
        {
            options: [
                { label: 'Owner', value: 'Owner' },
                { label: 'Company', value: 'Company' },
                { label: 'Location', value: 'Location' }
            ]
        }
    ];

    return (
        <React.Fragment>
            <div className="col-md-auto ms-auto">
                <div className="d-flex align-items-center gap-2">
                    <span className="text-muted">Sort by: </span>
                    <Select
                        className="mb-0"
                        value={sortBy}
                        onChange={handleSortBy}
                        options={sortOptions}
                        id="choices-single-default"
                    />
                </div>
            </div>
        </React.Fragment>
    );
};

const CompaniesGlobalFilter = () => {
    const [sortBy, setsortBy] = useState("Owner");

    function handlesortBy(sortBy: any) {
        setsortBy(sortBy);
    }

    const sortbyname: any = [
        {
            options: [
                { label: "Owner", value: "Owner" },
                { label: "Company", value: "Company" },
                { label: "Location", value: "Location" },
            ],
        },
    ];
    return (
        <React.Fragment>
            <div className="col-md-auto ms-auto">
                <div className="d-flex align-items-center gap-2">
                    <span className="text-muted">Sort by: </span>
                    <Select
                        className="mb-0"
                        value={sortBy}
                        onChange={handlesortBy}
                        options={sortbyname}
                        id="choices-single-default"
                    ></Select>
                </div>
            </div>
        </React.Fragment>
    );
};

const CryptoOrdersGlobalFilter = () => {
    return (
        <React.Fragment>
            <Col xl={2} md={6}>
                <div className="input-group">
                    <span className="input-group-text" id="basic-addon1"><i className="ri-calendar-2-line"></i></span>
                    <Flatpickr
                        placeholder="Select date"
                        className="form-control"
                        options={{
                            mode: "range",
                            dateFormat: "d M, Y"
                        }}
                    />
                </div>
            </Col>
            <Col xl={2} md={4}>
                <select className="form-control" data-choices data-choices-search-false name="choices-single-default"
                    id="choices-single-default">
                    <option defaultValue="all">Select Type</option>
                    <option value="Buy">Sell</option>
                    <option value="Sell">Buy</option>
                </select>
            </Col>
            <Col xl={2} md={4}>
                <select className="form-control" data-choices data-choices-search-false name="choices-single-default2"
                    id="choices-single-default2">
                    <option defaultValue="all">Select Status</option>
                    <option value="Successful">Successful</option>
                    <option value="Cancelled">Cancelled</option>
                    <option value="Pending">Pending</option>
                </select>
            </Col>
            <Col xl={1} md={4}>
                <button className="btn btn-success w-100">Filters</button>
            </Col>
        </React.Fragment>
    );
};

const InvoiceListGlobalSearch = () => {
    const [isStatus, setisStatus] = useState(null);


    function handleisStatus(isStatus: any) {
        setisStatus(isStatus);
    }

    const allstatus = [
        {
            options: [
                { label: "Status", value: "Status" },
                { label: "All", value: "All" },
                { label: "Unpaid", value: "Unpaid" },
                { label: "Paid", value: "Paid" },
                { label: "Cancel", value: "Cancel" },
                { label: "Refund", value: "Refund" },
            ],
        },
    ];
    return (
        <React.Fragment>
            <Col sm={4} xxl={3}>
                <Flatpickr
                    className="form-control bg-light border-light"
                    id="datepicker-publish-input"
                    placeholder="Select a date"
                    options={{
                        altInput: true,
                        altFormat: "F j, Y",
                        mode: "multiple",
                        dateFormat: "d.m.y",
                    }}
                />
            </Col>

            <Col sm={4} xxl={3}>
                <div className="input-light">
                    <Select
                        value={isStatus}
                        onChange={handleisStatus}
                        options={allstatus}
                        name="choices-single-default"
                        id="idStatus"
                    ></Select>
                </div>
            </Col>

            <Col sm={4} xxl={1}>
                <Button variant="primary" className="w-100">
                    <i className="ri-equalizer-fill me-1 align-bottom"></i>{" "}
                    Filters
                </Button>
            </Col>

        </React.Fragment>
    );
};

const TicketsListGlobalFilter = () => {
    return (
        <React.Fragment>
            <Col xxl={3} sm={4}>
                <Flatpickr
                    className="form-control"
                    placeholder="Select date range"
                    options={{
                        mode: "range",
                        dateFormat: "d M, Y"
                    }}
                />
            </Col>
            <Col xxl={3} sm={4}>
                <div className="input-light">
                    <select className="form-control" data-choices data-choices-search-false name="choices-single-default" id="idStatus">
                        <option value="">Status</option>
                        <option defaultValue="all">All</option>
                        <option value="Open">Open</option>
                        <option value="Inprogress">Inprogress</option>
                        <option value="Closed">Closed</option>
                        <option value="New">New</option>
                    </select>
                </div>
            </Col>
            <Col xxl={1} sm={4}>
                <button type="button" className="btn btn-primary w-100"> <i className="ri-equalizer-fill me-1 align-bottom"></i>
                    Filters
                </button>
            </Col>
        </React.Fragment>
    );
};

const NFTRankingGlobalFilter = () => {
    return (
        <React.Fragment>
            <Col xxl={2} sm={4} className="ms-auto">
                <div>
                    <select className="form-control" data-choices data-choices-search-false name="choices-single-default" id="idStatus">
                        <option value="All Time" defaultValue="">All Time</option>
                        <option value="1 Day">1 Day</option>
                        <option value="7 Days">7 Days</option>
                        <option value="15 Days">15 Days</option>
                        <option value="1 Month">1 Month</option>
                        <option value="6 Month">6 Month</option>
                    </select>
                </div>
            </Col>
        </React.Fragment>
    );
};

const TaskListGlobalFilter = () => {
    return (
        <React.Fragment>
            <div className="col-xxl-3 col-sm-4">
                <Flatpickr
                    placeholder="Select date range"
                    className="form-control bg-light border-light"
                    options={{
                        mode: "range",
                        dateFormat: "d M, Y"
                    }}
                />
            </div>

            <div className="col-xxl-3 col-sm-4">
                <div className="input-light">
                    <select className="form-control" data-choices data-choices-search-false name="status" id="idStatus">
                        <option value="">Status</option>
                        <option defaultValue="all"  >All</option>
                        <option value="New">New</option>
                        <option value="Pending">Pending</option>
                        <option value="Inprogress">Inprogress</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
            </div>
            <div className="col-xxl-1 col-sm-4">
                <button type="button" className="btn btn-primary w-100"> <i className="ri-equalizer-fill me-1 align-bottom"></i>
                    Filters
                </button>
            </div>
        </React.Fragment>
    );
};


const LeadsGlobalFilter = ({ onClickDelete }: any) => {
    return (
        <React.Fragment>
            <div className="col-sm-auto ms-auto">
                <div className="hstack gap-2">
                    <button className="btn btn-soft-danger" onClick={onClickDelete}
                    ><i className="ri-delete-bin-2-line"></i></button>
                    <button type="button" className="btn btn-info"
                    //  onClick={toggleInfo}
                    >
                        <i className="ri-filter-3-line align-bottom me-1"></i>{" "}
                        Fliters
                    </button>
                    <button
                        type="button"
                        className="btn btn-success add-btn"
                        id="create-btn"
                    // onClick={() => { setIsEdit(false); toggle(); }}
                    >
                        <i className="ri-add-line align-bottom me-1"></i> Add
                        Leads
                    </button>
                    <Dropdown>
                        <Dropdown.Toggle
                            className="btn btn-soft-info btn-icon fs-14 arrow-none"
                            type="button"
                            id="dropdownMenuButton1"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >
                            <i className="ri-settings-4-line"></i>
                        </Dropdown.Toggle>
                        <Dropdown.Menu
                        >
                            <li>
                                <Dropdown.Item>
                                    Copy
                                </Dropdown.Item>
                            </li>
                            <li>
                                <Dropdown.Item>
                                    Move to pipline
                                </Dropdown.Item>
                            </li>
                            <li>
                                <Dropdown.Item>
                                    Add to exceptions
                                </Dropdown.Item>
                            </li>
                            <li>
                                <Dropdown.Item>
                                    Switch to common form view
                                </Dropdown.Item>
                            </li>
                            <li>
                                <Dropdown.Item>
                                    Reset form view to default
                                </Dropdown.Item>
                            </li>
                        </Dropdown.Menu>
                    </Dropdown>
                </div>
            </div>
        </React.Fragment>
    );
};

export {
    ProductsGlobalFilter,
    CustomersGlobalFilter,
    OrderGlobalFilter,
    ContactsGlobalFilter,
    CompaniesGlobalFilter,
    CryptoOrdersGlobalFilter,
    InvoiceListGlobalSearch,
    TicketsListGlobalFilter,
    NFTRankingGlobalFilter,
    TaskListGlobalFilter,
    LeadsGlobalFilter
};