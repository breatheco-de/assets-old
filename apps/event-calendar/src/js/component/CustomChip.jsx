import React from "react";
import PropTypes from 'prop-types';
import classNames from 'classnames';
import Avatar from '@material-ui/core/Avatar';
import Chip from '@material-ui/core/Chip';
import Tooltip from '@material-ui/core/Tooltip';

function CustomChip(props){
    var chip = (
        <Chip
            avatar={
                <Avatar
                    className={props.classes.chipAvatar}
                >
                    {props.icon}
                </Avatar>
            }
            label={props.label.replace(/_/g, " ")}
            className={classNames(props.classes.margin, props.classes.chip, (props.clickable ? props.classes.clickable: '') )}
            onClick={props.onClick}
            //clickable={props.clickable}
        />
    );
    
    if(props.tooltipTitle) return (<Tooltip id="tooltip-icon" title={props.tooltipTitle}>{chip}</Tooltip>);
    
    return chip;
    
}
CustomChip.propTypes = {
  classes: PropTypes.object,
  label: PropTypes.string,
  onClick: PropTypes.func,
  clickable: PropTypes.bool,
  tooltipTitle: PropTypes.string,
  icon: PropTypes.element
};

export default CustomChip;