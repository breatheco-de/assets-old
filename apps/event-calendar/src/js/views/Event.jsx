import React from "react";
import Moment from "moment";
import PropTypes from 'prop-types';
import ReactGA from 'react-ga';
import classNames from 'classnames';
import { withStyles } from '@material-ui/core/styles';
import Card from '@material-ui/core/Card';
import CardHeader from '@material-ui/core/CardHeader';
import CardContent from '@material-ui/core/CardContent';
import CardActions from '@material-ui/core/CardActions';
import Avatar from '@material-ui/core/Avatar';
import IconButton from '@material-ui/core/IconButton';
import GroupIcon from '@material-ui/icons/Group';
import CardMedia from '@material-ui/core/CardMedia';
import LocationCityIcon from '@material-ui/icons/LocationCity';
import DirectionsIcon from '@material-ui/icons/Directions';
import LabelIcon from '@material-ui/icons/Label';
import CheckIcon from '@material-ui/icons/Check';
import AnnouncementIcon from '@material-ui/icons/Announcement';
import Button from '@material-ui/core/Button';
import Tooltip from '@material-ui/core/Tooltip';
import CircularProgress from '@material-ui/core/CircularProgress';
import CustomChip from '../component/CustomChip.jsx';
import ReactHtmlParser from 'react-html-parser';
import {
  FacebookShareButton,
  GooglePlusShareButton,
  LinkedinShareButton,
  TwitterShareButton,
  TelegramShareButton,
  WhatsappShareButton,
  PinterestShareButton,
  RedditShareButton,
  EmailShareButton,
  
  FacebookIcon,
  TwitterIcon,
  GooglePlusIcon,
  LinkedinIcon,
  PinterestIcon,
  TelegramIcon,
  WhatsappIcon,
  RedditIcon,
  EmailIcon
} from 'react-share';

import {Consumer} from '../stores/AppContext.jsx';

const styles = theme => ({
  card: {
    maxWidth: 1000,
    marginLeft: 'auto',
    marginRight: 'auto',
    marginBottom: 44
  },
  cardHeader: {
      paddingBottom:0
  },
  media: {
    height: 0,
    paddingTop: '56.25%' // 16:9
  },
  actions: {
    display: 'flex',
    flexWrap: 'wrap',
    padding: '8px 12px'
  },
  expand: {
    transform: 'rotate(0deg)',
    transition: theme.transitions.create('transform', {
      duration: theme.transitions.duration.shortest
    }),
    marginLeft: 'auto'
  },
  expandOpen: {
    transform: 'rotate(180deg)'
  },
  avatar: {
    margin: 10
  },
  bigAvatar: {
    width: 60,
    height: 60
  },
  margin: {
    marginRight: theme.spacing.unit * 2,
    marginBottom: theme.spacing.unit
  },
  progress: {
    margin: theme.spacing.unit * 2
  },
  badgesContainer: {
    padding: theme.spacing.unit+"px 0",
    display: "flex",
    justifyContent: "flex-start",
    alignItems: "flex-end",
    flexWrap: "wrap"
  },
  chip: {
    marginLeft: 0,
    color: theme.palette.primary.light,
    backgroundColor: theme.palette.primary.contrastText,
    border: "1px solid "+theme.palette.primary.light
  },
  chipAvatar: {
    backgroundColor: theme.palette.primary.contrastText,
    height:30,
    width:30
  },
  clickable: {
    boxShadow: "0px 1px 5px 0px rgba(0, 0, 0, 0.2), 0px 2px 2px 0px rgba(0, 0, 0, 0.14), 0px 3px 1px -2px rgba(0, 0, 0, 0.12)"
  },
  fab: {
    position: 'fixed',
    bottom: theme.spacing.unit * 2,
    right: theme.spacing.unit * 2
  },
  eventContent:{
      padding: theme.spacing.unit
  }
});

class Event extends React.Component {
    
    render(){
        const { classes } = this.props;

        ReactGA.pageview(window.location.pathname + window.location.search);

        return (
            <Consumer>
                {({ state }) => 
                    {
                        const event = state.events.find( event => parseInt(event.id) === parseInt(this.props.match.params.theid) );
    
                        if(!event){ 
                            
                            return(<CircularProgress className={classes.progress} color="secondary" />);
                        
                        }else{
                        
                            let eventDay, eventTime = eventDay = "TBA";
                            if( event.event_date !== null ){
                                eventDay = event.event_date.replace(/\s/g, 'T').replace(/-/g, '').replace(/:/g, '');
                                eventDay = Moment(eventDay);
                                eventTime = eventDay.format("h:mm a").toString();
                                eventDay = eventDay.format("MMMM D").toString();
                            }
                            
                            return(
                                <Card className={classes.card}>
                                    <CardHeader
                                        avatar={
                                            <Avatar
                                                aria-label="Recipe"
                                                src={event.logo_url || "https://pbs.twimg.com/profile_images/930433054371434496/v8GNrusZ_400x400.jpg"}
                                                className={classNames(classes.avatar, classes.bigAvatar)}
                                            />
                                        }
                                        title={event.title}
                                        subheader={eventDay+" "+eventTime}
                                        className={classes.cardHeader}
                                    />
                                    {
                                        event.banner_url &&
                                        <CardMedia
                                            className={classes.media}
                                            image={event.banner_url}
                                        />
                                    }
                                    <CardContent>
                                        <div className={classes.badgesContainer}>
                                            { event.address && (
                                                <CustomChip 
                                                    classes={classes} 
                                                    label={event.address}
                                                    clickable={true}
                                                    onClick={() => window.open("https://maps.google.com/maps?q="+event.address , "_blank")}
                                                    icon={<DirectionsIcon />}
                                                />
                                                )
                                            }
                                            { event.capacity && (
                                                <CustomChip 
                                                    classes={classes} 
                                                    label={event.capacity}
                                                    tooltipTitle="Capacity"
                                                    icon={<GroupIcon/>}
                                                />
                                                )
                                            }
                                            { event.type && (
                                                <CustomChip 
                                                    classes={classes} 
                                                    label={event.type}
                                                    tooltipTitle="Type"
                                                    icon={<LabelIcon/>}
                                                />
                                                )
                                            }
                                            { event.city_slug && (
                                                <CustomChip 
                                                    classes={classes} 
                                                    label={event.city_slug.toUpperCase()}
                                                    icon={<LocationCityIcon/>}
                                                />
                                                )
                                            }
                                            { event.invite_only === "1" && (
                                                <CustomChip 
                                                    classes={classes} 
                                                    label="Invitation required"
                                                    tooltipTitle="Invitation Only"
                                                    icon={<AnnouncementIcon/>}
                                                />
                                                )
                                            }
                                        </div>
                                        <div className={classes.eventContent}>
                                            {ReactHtmlParser(event.description.replace(/<br>/g, ''))}
                                        </div>
                                    </CardContent>
                                    <CardActions className={classes.actions} disableActionSpacing>
                                        
                                        <IconButton aria-label="Facebook Share">
                                            <FacebookShareButton 
                                                url={window.location.href}> 
                                                <FacebookIcon size={32} round />
                                            </FacebookShareButton>
                                        </IconButton>
                                        <IconButton aria-label="Facebook Share">
                                            <GooglePlusShareButton url={window.location.href}>
                                                <GooglePlusIcon size={32} round />
                                            </GooglePlusShareButton>
                                        </IconButton>
                                        <IconButton aria-label="Facebook Share">
                                            <TwitterShareButton url={window.location.href}>
                                                <TwitterIcon size={32} round />
                                            </TwitterShareButton >
                                        </IconButton>
                                        <IconButton aria-label="Facebook Share">
                                            <LinkedinShareButton url={window.location.href}>
                                                <LinkedinIcon size={32} round />
                                            </LinkedinShareButton >
                                        </IconButton>
                                        <IconButton aria-label="Facebook Share">
                                            <TelegramShareButton url={window.location.href}>
                                                <TelegramIcon size={32} round />
                                            </TelegramShareButton >
                                        </IconButton>
                                        <IconButton aria-label="Facebook Share">
                                            <WhatsappShareButton url={window.location.href}>
                                                <WhatsappIcon size={32} round />
                                            </WhatsappShareButton >
                                        </IconButton>
                                        {
                                            event.banner_url &&
                                            <IconButton aria-label="Facebook Share">
                                                <PinterestShareButton url={window.location.href} media={event.banner_url}>
                                                    <PinterestIcon size={32} round />
                                                </PinterestShareButton >
                                            </IconButton>
                                        }
                                        <IconButton aria-label="Facebook Share">
                                            <RedditShareButton url={window.location.href}>
                                                <RedditIcon size={32} round />
                                            </RedditShareButton >
                                        </IconButton>
                                        <IconButton aria-label="Facebook Share">
                                            <EmailShareButton url={window.location.href}>
                                                <EmailIcon size={32} round />
                                            </EmailShareButton >
                                        </IconButton>
                                        
                                    </CardActions>
                                    <Button 
                                        id="fabButton"
                                        variant="fab" 
                                        color="secondary" 
                                        className={classNames(classes.button, classes.fab)} 
                                        onClick={() => window.open(event.url,"_blank")}>
                                        <Tooltip open={true} title="RSVP ➤ " placement="left">
                                            <CheckIcon/>
                                        </Tooltip>
                                    </Button>
                                </Card>
                            );
                        }
                    }
                }
            </Consumer>
        );
    }
}
export default withStyles(styles,{ withTheme: true })(Event);

Event.propTypes = {
    classes: PropTypes.object,
    match: PropTypes.object
};