import React from 'react';
import PropTypes from 'prop-types';


class Modal extends React.Component {
    constructor(props){
        super(props);
        
        this.state = {
            value:''
        };
        
        document.body.classList.add('modal-open');
        
    }
    
    render(){
        
        return(
            <div>
                <div className={"modal fade" + (this.props.show ? ' show' : '')} id="exampleModal" tabIndex="-1" role="dialog" style={{display: (this.props.show) ? 'block' : 'none'}} >
                    <div className="modal-dialog" role="document">
                        <div className="modal-content">
                            <div className="modal-header">
                                <h5 className="modal-title" id="exampleModalLabel">{this.props.title}</h5>
                                <button type="button" className="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div className="modal-body">
                                {this.props.body}
                                This a DEMO modal.
                            </div>
                            <div className="modal-footer">
                                <button type="button" className="btn btn-primary" data-dismiss="modal">CANCEL</button>
                                <button type="button" className="btn btn-outline-danger" onClick={(e) => this.props.action()}>DELETE</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div className={"modal-backdrop fade" + (this.props.show ? ' show' : '')}></div>
            </div>
        );
    }
}

Modal.propTypes = {
   title: PropTypes.string,
   body: PropTypes.string,
   action: PropTypes.func,
   show: PropTypes.bool
};

export default Modal;