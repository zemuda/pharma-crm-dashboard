import BreadCrumb from '@/Components/Common/BreadCrumb';
import React, { useState } from 'react';
import { Card, Col, Container, Form, Row } from 'react-bootstrap';
//Import Flatepicker
// import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
// import { CKEditor } from '@ckeditor/ckeditor5-react';
import Flatpickr from 'react-flatpickr';

import Dropzone from 'react-dropzone';

//Import Images
import { SimpleEditor } from '@/custom/tiptap/simple/simple-editor';
import { Head, Link } from '@inertiajs/react';
import Layout from '../../../Layouts';

const CreateATCCodes = () => {
    const SingleOptions = [
        { value: 'Watches', label: 'Watches' },
        { value: 'Headset', label: 'Headset' },
        { value: 'Sweatshirt', label: 'Sweatshirt' },
        { value: '20% off', label: '20% off' },
        { value: '4 star', label: '4 star' },
    ];

    const [selectedMulti, setselectedMulti] = useState<any>(null);

    const handleMulti = (selectedMulti: any) => {
        setselectedMulti(selectedMulti);
    };

    //Dropzone file upload
    const [selectedFiles, setselectedFiles] = useState<any>([]);

    const handleAcceptedFiles = (files: any) => {
        files.map((file: any) =>
            Object.assign(file, {
                preview: URL.createObjectURL(file),
                formattedSize: formatBytes(file.size),
            }),
        );
        setselectedFiles(files);
    };

    /**
     * Formats the size
     */
    const formatBytes = (bytes: any, decimals = 2) => {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return (
            parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i]
        );
    };

    return (
        <React.Fragment>
            <Head title="Create Project | Velzon - React Admin & Dashboard Template" />
            <div className="page-content">
                <Container fluid>
                    <BreadCrumb title="Create ATC Code" pageTitle="ATC Codes" />
                    <Row>
                        <Col lg={8}>
                            <Card>
                                <Card.Body>
                                    <div className="mb-3">
                                        <Form.Label
                                            className="form-label"
                                            htmlFor="atc-code-title-input"
                                        >
                                            ATC Code Title
                                        </Form.Label>
                                        <Form.Control
                                            type="text"
                                            className="form-control"
                                            id="atc-code-title-input"
                                            placeholder="Enter project title"
                                        />
                                    </div>

                                    <div className="mb-3">
                                        <Form.Label
                                            className="form-label"
                                            htmlFor="atc-code-thumbnail-img"
                                        >
                                            Thumbnail Image
                                        </Form.Label>
                                        <Form.Control
                                            className="form-control"
                                            id="atc-code-thumbnail-img"
                                            type="file"
                                            accept="image/png, image/gif, image/jpeg"
                                        />
                                    </div>

                                    <div className="mb-3">
                                        <Form.Label className="form-label">
                                            Project Description
                                        </Form.Label>
                                       <SimpleEditor/>
                                    </div>

                                    <Row>
                                        <Col lg={4}>
                                            <div className="mb-lg-0 mb-3">
                                                <Form.Label
                                                    htmlFor="choices-priority-input"
                                                    className="form-label"
                                                >
                                                    Priority
                                                </Form.Label>
                                                <select
                                                    className="form-select"
                                                    data-choices
                                                    data-choices-search-false
                                                    id="choices-priority-input"
                                                >
                                                    <option defaultValue="High">
                                                        High
                                                    </option>
                                                    <option value="Medium">
                                                        Medium
                                                    </option>
                                                    <option value="Low">
                                                        Low
                                                    </option>
                                                </select>
                                            </div>
                                        </Col>
                                        <Col lg={4}>
                                            <div className="mb-lg-0 mb-3">
                                                <Form.Label
                                                    htmlFor="choices-status-input"
                                                    className="form-label"
                                                >
                                                    Status
                                                </Form.Label>
                                                <select
                                                    className="form-select"
                                                    data-choices
                                                    data-choices-search-false
                                                    id="choices-status-input"
                                                >
                                                    <option defaultValue="Inprogress">
                                                        Inprogress
                                                    </option>
                                                    <option value="Completed">
                                                        Completed
                                                    </option>
                                                </select>
                                            </div>
                                        </Col>
                                        <Col lg={4}>
                                            <div>
                                                <Form.Label
                                                    htmlFor="datepicker-deadline-input"
                                                    className="form-label"
                                                >
                                                    Deadline
                                                </Form.Label>
                                                <Flatpickr
                                                    className="form-control"
                                                    options={{
                                                        dateFormat: 'd M, Y',
                                                    }}
                                                    placeholder="Enter due date"
                                                />
                                            </div>
                                        </Col>
                                    </Row>
                                </Card.Body>
                            </Card>
                            <Card>
                                <Card.Header>
                                    <h5 className="card-title mb-0">
                                        Attached files
                                    </h5>
                                </Card.Header>
                                <Card.Body>
                                    <div>
                                        <p className="text-muted">
                                            Add Attached files here.
                                        </p>

                                        <Dropzone
                                            onDrop={(acceptedFiles) => {
                                                handleAcceptedFiles(
                                                    acceptedFiles,
                                                );
                                            }}
                                        >
                                            {({
                                                getRootProps,
                                                getInputProps,
                                            }) => (
                                                <div className="dropzone dz-clickable">
                                                    <div
                                                        className="dz-message needsclick"
                                                        {...getRootProps()}
                                                    >
                                                        <div className="mb-3">
                                                            <i className="display-4 ri-upload-cloud-2-fill text-muted" />
                                                        </div>
                                                        <h4>
                                                            Drop files here or
                                                            click to upload.
                                                        </h4>
                                                    </div>
                                                </div>
                                            )}
                                        </Dropzone>

                                        <ul
                                            className="list-unstyled mb-0"
                                            id="dropzone-preview"
                                        >
                                            {selectedFiles.map(
                                                (f: any, i: any) => {
                                                    return (
                                                        <Card
                                                            className="dz-processing dz-image-preview dz-success dz-complete mt-1 mb-0 border shadow-none"
                                                            key={i + '-file'}
                                                        >
                                                            <div className="p-2">
                                                                <Row className="align-items-center">
                                                                    <Col className="col-auto">
                                                                        <img
                                                                            data-dz-thumbnail=""
                                                                            height="80"
                                                                            className="avatar-sm bg-light rounded"
                                                                            alt={
                                                                                f.name
                                                                            }
                                                                            src={
                                                                                f.preview
                                                                            }
                                                                        />
                                                                    </Col>
                                                                    <Col>
                                                                        <Link
                                                                            href="#"
                                                                            className="font-weight-bold text-muted"
                                                                        >
                                                                            {
                                                                                f.name
                                                                            }
                                                                        </Link>
                                                                        <p className="mb-0">
                                                                            <strong>
                                                                                {
                                                                                    f.formattedSize
                                                                                }
                                                                            </strong>
                                                                        </p>
                                                                    </Col>
                                                                </Row>
                                                            </div>
                                                        </Card>
                                                    );
                                                },
                                            )}
                                        </ul>
                                    </div>
                                </Card.Body>
                            </Card>

                            <div className="mb-4 text-end">
                                <button
                                    type="submit"
                                    className="btn btn-danger me-1 w-sm"
                                >
                                    Delete
                                </button>
                                <button
                                    type="submit"
                                    className="btn btn-secondary me-1 w-sm"
                                >
                                    Draft
                                </button>
                                <button
                                    type="submit"
                                    className="btn btn-success w-sm"
                                >
                                    Create
                                </button>
                            </div>
                        </Col>

                        <Col lg={4}>
                            <div className="card">
                                <div className="card-header">
                                    <h5 className="card-title mb-0">Privacy</h5>
                                </div>
                                <Card.Body>
                                    <div>
                                        <Form.Label
                                            htmlFor="choices-privacy-status-input"
                                            className="form-label"
                                        >
                                            Status
                                        </Form.Label>
                                        <select
                                            className="form-select"
                                            data-choices
                                            data-choices-search-false
                                            id="choices-privacy-status-input"
                                        >
                                            <option defaultValue="Private">
                                                Private
                                            </option>
                                            <option value="Team">Team</option>
                                            <option value="Public">
                                                Public
                                            </option>
                                        </select>
                                    </div>
                                </Card.Body>
                            </div>
                        </Col>
                    </Row>
                </Container>
            </div>
        </React.Fragment>
    );
};
CreateATCCodes.layout = (page: any) => <Layout children={page} />;
export default CreateATCCodes;
