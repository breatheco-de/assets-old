import React from "react";

export default ({ onConfirm, props }) => (
    <div className="modal-not-found">
        <h3 className="text-center">The email was not found, do you want to register a new one?</h3>
        <div className="center-btn-modal">
            <button type="button" className="btn btn-outline-danger text-center" onClick={()=>onConfirm(false)}>No</button>
            <button type="button" className="btn btn-outline-primary text-center" onClick={()=>onConfirm(true)}>Yes</button>
        </div>
    </div>
);