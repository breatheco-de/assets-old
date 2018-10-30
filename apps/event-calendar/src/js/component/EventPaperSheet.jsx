import React from 'react';
import PropTypes from 'prop-types';
import classNames from 'classnames';
import { withStyles } from '@material-ui/core/styles';
import { Link } from "react-router-dom";
import moment from "moment";

import Paper from '@material-ui/core/Paper';
import Typography from '@material-ui/core/Typography';
import Avatar from '@material-ui/core/Avatar';
import Button from '@material-ui/core/Button';
import CustomChip from './CustomChip.jsx';
import Flag from "react-flags";
import DirectionsIcon from '@material-ui/icons/LocationOn';
import FancyButton from './FancyButton.jsx';

const styles = theme => ({
  root: theme.mixins.gutters({
    paddingLeft: theme.spacing.unit*4,
    paddingRight: theme.spacing.unit*4,
    paddingTop: theme.spacing.unit*4,
    paddingBottom: theme.spacing.unit*4,
    marginTop: theme.spacing.unit * 3
  }),
  root2: {
      display: "flex",
      flexWrap: "wrap"
  },
  fixItem: {
    flex: '2 1 auto',
    minWidth: "67%",
    flexWrap: "wrap",
    overflow: "hidden",
    textOverflow: "ellipsis"
  },
  inlineInfo: {
    flex: '1 1 auto', 
    margin: "10px 10px"
  },
  avatar: {
    margin: 10
  },
  bigAvatar: {
    width: 20,
    height: 20
  },
  buttoners:{
    justifyContent: 'space-evenly',
    alignSelf: 'center',
    padding: theme.spacing.unit*3+"px 0 0"
  },
  chip: {
    marginLeft: 0,
    color: theme.palette.primary.dark,
    backgroundColor: theme.palette.primary.contrastText
  },
  chipAvatar: {
    backgroundColor: theme.palette.primary.contrastText
  },
  clickable: {
    margin:5,
    boxShadow: "0px 1px 5px 0px rgba(0, 0, 0, 0.2), 0px 2px 2px 0px rgba(0, 0, 0, 0.14), 0px 3px 1px -2px rgba(0, 0, 0, 0.12)"
  },
  soon: {
    color: theme.palette.secondary.main,
    fontWeight: "bold"
  }
});

class EventPaperSheet extends React.Component {

    stripHTML(html){
      var doc = new DOMParser().parseFromString(html, 'text/html');
      return doc.body.textContent || "";
    }
    
    render(){
        const { classes } = this.props;
        const event = this.props.event;
        const types = ["intro_to_coding","coding_weekend","workshop","hackathon","4geeks_night","other"];
        
        let eventDay, eventTime = eventDay = "TBA";
        let aDate = event.event_date || event.kickoff_date || null; 
        let soon = "";
        if( moment(aDate).isValid() ){
            eventDay = aDate.replace(/\s/g, 'T');
            eventDay = eventDay.replace(/-/g, '').replace(/:/g, '');
            eventDay = moment(eventDay);
            soon = eventDay.diff(moment(), 'days') < 20 && "soon";
            eventTime = eventDay.get("h") !== 0 ? eventDay.format("h:mm a").toString() : null;
            eventDay = eventDay.format("MMMM D YYYY").toString();
        }
        
        const lang = event.lang || event.language || null;
        const title = event.title || "New Cohort";
        const description = this.stripHTML(event.description);
        const cta = types.indexOf(event.type) > -1 ? "RSVP" : "APPLY";
        
        const titleComp = (
            <Typography variant="title" component="h2" style={{display: "inline-block", textDecoration: "underline", fontSize: "1.6rem", lineHeight: "1.4em"}}>
                {title}
            </Typography>);
        
        return ( 
            <Paper elevation={1} className={classNames(classes.root, classes.root2)} style={{justifyContent: "space-between"}}>
                <div className={classes.fixItem}>
                    { event.type !== 'course' ? 
                        (<Link to={"/event/"+event.id}> {titleComp}</Link>) 
                        :
                        (<a href={"https://www.4geeksacademy.co/course/"+event.slug}> {titleComp} </a>)
                    }
                    <br/>
                    <div style={{display: "flex", flexWrap:"wrap"}}>
                        <div style={{flex: "1 1 500px"}}>
                            <Typography variant="body1" style={{padding:"10px 0", lineHeight:"1.6rem"}}>
                                {
                                  description && description.substring(0, description.indexOf('.',200)+1)
                                }
                            </Typography>
                            {
                              event.banner_url ?
                                  <FancyButton 
                                    image={event.banner_url} 
                                    text={event.address} 
                                    onClick={() => window.open(event.type === 'course' ? "https://www.4geeksacademy.co/course/"+event.slug:"https://maps.google.com/maps?q="+event.address , "_blank")}
                                  />
                              :
                              event.address &&
                                  <div className={classes.inlineInfo}>
                                      <Typography variant="caption">Where:</Typography>
                                      <CustomChip
                                          classes={classes} 
                                          clickable={true}
                                          onClick={() => window.open("https://maps.google.com/maps?q="+event.address , "_blank")}
                                          icon={
                                              <Avatar
                                                  src={event.logo_url}
                                              />
                                          }
                                          label={event.address.substring(0, event.address.indexOf(',', event.address.indexOf(',')+1))}
                                        />
                                  </div>
                            }
                        </div>
                        <div style={{flex: "1 1 80px"}}>
                            <div className={classes.inlineInfo}>
                                <Typography variant="caption">Date:</Typography>
                                <Typography style={{display:"inline"}} className={classes[soon]}>{eventDay}</Typography> 
                            </div>
                            { eventTime ?
                                <div className={classes.inlineInfo}>
                                    <Typography variant="caption">Time:</Typography> <Typography>{eventTime}</Typography>
                                </div>
                            :
                                <div className={classes.inlineInfo}>
                                    <Typography variant="caption">Duration:</Typography> <Typography>{event.hr_duration} hours ({event.week_duration} weeks)</Typography>
                                </div>
                            }
                            {event.location_slug &&
                            <div className={classes.inlineInfo}>
                                <Typography variant="caption">Location:</Typography>
                                <CustomChip
                                    classes={classes} 
                                    clickable={true}
                                    onClick={() => window.open("https://www.4geeksacademy.co/location/"+event.location_slug , "_blank")}
                                    icon={<DirectionsIcon />}
                                    label={event.location_slug.replace(/-/g, ' ')}
                                  />
                            </div>
                            }
                            {lang &&
                            <div className={classes.inlineInfo}>
                                <Typography variant="caption">Language:</Typography>
                                <Flag
                                  name={lang === 'en' ? "US" : lang.toUpperCase()}
                                  format="png"
                                  pngSize={24}
                                  alt="USA Flag"
                                  basePath="./img/flags"
                                />
                            </div>
                                
                            }
                        </div>
                    </div>
                    <div className={classNames(classes.root2, classes.buttoners)}>
                        <a href={event.url} style={{textDecoration: 'none'}}>
                            <Button size="large" variant={"raised"} color="secondary" className={classNames(classes.button, classes[cta])}>
                                {cta}
                            </Button>
                        </a>
                        <Link to={"/event/"+event.id} style={{textDecoration: 'none'}}>
                            <Button size="large" variant="flat" color="secondary" className={classes.button}>
                              Read More
                            </Button>
                        </Link>
                    </div>
                </div>
            </Paper>
      );
    }
}

EventPaperSheet.propTypes = {
  classes: PropTypes.object.isRequired,
  event: PropTypes.object.isRequired
};

export default withStyles(styles)(EventPaperSheet);